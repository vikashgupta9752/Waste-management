<?php

namespace App\Http\Controllers;

use App\Models\WasteRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SmartFeatureController extends Controller
{
    /**
     * Predict waste generation based on past data
     */
    public function predictWaste()
    {
        // Simulation logic: Average requests per day of week over the last 4 weeks
        $predictions = [];
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        
        foreach ($days as $index => $day) {
            // Mock data for simulation if real data is insufficient
            $predictions[$day] = WasteRequest::whereRaw("strftime('%w', created_at) = ?", [$index])
                ->count() ?: rand(5, 15);
        }

        return response()->json($predictions);
    }

    /**
     * Get heatmap data (lat/lng/intensity)
     */
    public function getHeatmapData()
    {
        $data = WasteRequest::select('latitude', 'longitude')
            ->where('created_at', '>=', Carbon::now()->subDays(30))
            ->get()
            ->map(function ($request) {
                return [$request->latitude, $request->longitude, 1.0]; // [lat, lng, intensity]
            });

        return response()->json($data);
    }

    /**
     * AI-Based Waste Segregation Guide
     */
    public function segregationGuide()
    {
        return view('citizen.segregation-guide');
    }

    public function processSegregation(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048',
        ]);

        // Simulation of AI processing
        $categories = ['Dry', 'Wet', 'Hazardous', 'Recyclable'];
        $suggested = $categories[array_rand($categories)];
        
        $instructions = [
            'Dry' => 'Clean the items and place them in the blue bin. Avoid food contamination.',
            'Wet' => 'Compost if possible, otherwise place in the green bin for biogas processing.',
            'Hazardous' => 'Keep separate and wait for specialized hazardous waste collection pickup.',
            'Recyclable' => 'Remove labels and rinse containers. Place in the yellow recycling bin.',
        ];

        return back()->with([
            'success' => true,
            'category' => $suggested,
            'instructions' => $instructions[$suggested]
        ]);
    }

    /**
     * Live Vehicle Tracking Simulation
     */
    public function getVehicleLocation($driver_id)
    {
        // Simulation: Move the vehicle slightly based on the current minute
        $baseLat = 23.8103; // Base lat for simulation (e.g., Dhaka)
        $baseLng = 90.4125;
        
        $offset = (date('i') % 10) * 0.001;
        
        return response()->json([
            'driver_id' => $driver_id,
            'latitude' => $baseLat + $offset,
            'longitude' => $baseLng + $offset,
            'status' => 'moving',
            'updated_at' => now()->toDateTimeString(),
        ]);
    }
}
