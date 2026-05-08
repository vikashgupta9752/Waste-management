<?php

namespace App\Services;

use App\Models\WasteRequest;
use App\Models\Prediction;
use App\Models\Insight;
use App\Models\Bin;
use App\Models\User;
use App\Models\SimulationLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SmartCityService
{
    /**
     * Predict future waste generation based on 7-day trend.
     */
    public function predictWaste($area = null)
    {
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(7);

        $query = WasteRequest::where('created_at', '>=', $startDate);
        if ($area) {
            $query->where('address', 'LIKE', "%$area%");
        }

        $dailyStats = $query->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        if ($dailyStats->count() < 3) {
            return 5; // Default fallback if not enough data
        }

        $counts = $dailyStats->pluck('count')->toArray();
        $avg = array_sum($counts) / count($counts);
        
        // Trend: compare last 3 days to first 4
        $last3 = array_slice($counts, -3);
        $first4 = array_slice($counts, 0, 4);
        
        $avgLast3 = array_sum($last3) / 3;
        $avgFirst4 = count($first4) > 0 ? array_sum($first4) / count($first4) : $avgLast3;
        
        $trendFactor = $avgFirst4 > 0 ? ($avgLast3 / $avgFirst4) : 1;
        $prediction = round($avg * $trendFactor, 2);

        // Save prediction for history/charting
        Prediction::updateOrCreate(
            ['area_name' => $area ?? 'City-Wide', 'prediction_date' => $today->addDay()->toDateString()],
            ['predicted_value' => $prediction]
        );

        return $prediction;
    }

    /**
     * Generate dynamic insights based on current metrics.
     */
    public function generateInsights()
    {
        $insights = [];

        // 1. High Waste Areas
        $topArea = WasteRequest::select('address', DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy('address')
            ->orderBy('count', 'DESC')
            ->first();

        if ($topArea && $topArea->count > 10) {
            $insights[] = [
                'message' => "High waste density detected in {$topArea->address}. Consider increasing fleet frequency.",
                'type' => 'danger'
            ];
        }

        // 2. Weekly Trend
        $thisWeek = WasteRequest::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $lastWeek = WasteRequest::whereBetween('created_at', [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()])->count();

        if ($lastWeek > 0) {
            $diff = (($thisWeek - $lastWeek) / $lastWeek) * 100;
            if (abs($diff) > 10) {
                $status = $diff > 0 ? 'increased' : 'decreased';
                $insights[] = [
                    'message' => "Total waste has {$status} by " . round(abs($diff), 1) . "% compared to last week.",
                    'type' => $diff > 0 ? 'warning' : 'success'
                ];
            }
        }

        // 3. Driver Efficiency
        $drivers = User::where('role', 'driver')->withCount(['assignments' => function($q) {
            $q->where('status', 'completed');
        }])->get();

        foreach ($drivers as $driver) {
            $total = $driver->assignments()->count();
            if ($total > 5) {
                $rate = ($driver->assignments_count / $total) * 100;
                if ($rate < 50) {
                    $insights[] = [
                        'message' => "Low performance detected for Driver {$driver->name} (Completion rate: " . round($rate, 1) . "%).",
                        'type' => 'warning'
                    ];
                }
            }
        }

        // 4. Frequent Bins
        $fullBins = Bin::where('fill_level', '>=', 90)->count();
        if ($fullBins > 3) {
            $insights[] = [
                'message' => "{$fullBins} smart bins are currently at critical capacity. Immediate attention required.",
                'type' => 'danger'
            ];
        }

        // Save new insights
        foreach ($insights as $insight) {
            Insight::firstOrCreate(['message' => $insight['message']], [
                'type' => $insight['type'],
                'is_read' => false
            ]);
        }

        return Insight::where('is_read', false)->latest()->take(5)->get();
    }

    /**
     * Simulate a high-load scenario.
     */
    public function triggerSimulation($type = 'heavy_waste')
    {
        SimulationLog::create([
            'event' => $type,
            'details' => ['triggered_at' => Carbon::now()->toDateTimeString()]
        ]);

        if ($type === 'heavy_waste') {
            // Fill all bins to 90-100%
            Bin::where('status', 'active')->update(['fill_level' => rand(90, 100), 'status' => 'full']);

            // Create 10 random waste requests
            $citizens = User::where('role', 'citizen')->pluck('id')->toArray();
            $categories = \App\Models\WasteCategory::pluck('id')->toArray();
            
            for ($i = 0; $i < 10; $i++) {
                WasteRequest::create([
                    'user_id' => $citizens[array_rand($citizens)],
                    'waste_category_id' => $categories[array_rand($categories)],
                    'address' => 'Simulation Street ' . rand(1, 100),
                    'latitude' => 23.8103 + (rand(-50, 50) / 1000),
                    'longitude' => 90.4125 + (rand(-50, 50) / 1000),
                    'status' => 'pending'
                ]);
            }
        }

        return true;
    }

    /**
     * Calculate KPIs for the dashboard.
     */
    public function getKPIs()
    {
        $total = WasteRequest::count();
        $completed = WasteRequest::whereIn('status', ['collected', 'disposed'])->count();
        
        $avgPickupTime = 0;
        $completedRequests = WasteRequest::where('status', 'disposed')
            ->whereNotNull('created_at')
            ->whereNotNull('updated_at')
            ->get();

        if ($completedRequests->count() > 0) {
            $totalTime = 0;
            foreach ($completedRequests as $req) {
                $totalTime += $req->updated_at->diffInMinutes($req->created_at);
            }
            $avgPickupTime = round($totalTime / $completedRequests->count(), 1);
        }

        return [
            'completion_rate' => $total > 0 ? round(($completed / $total) * 100, 1) : 0,
            'avg_pickup_time' => $avgPickupTime,
            'efficiency_index' => $total > 0 ? round(($completed / $total) * 10, 1) : 0,
            'trees_saved' => round($completed * 0.05, 2), // Mock formula
            'carbon_reduced' => round($completed * 1.2, 1) // kg of CO2
        ];
    }
}
