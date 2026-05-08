<?php

namespace App\Services;

use App\Models\User;
use App\Models\WasteRequest;
use App\Models\Assignment;
use Illuminate\Support\Facades\DB;

class AutoAssignService
{
    /**
     * Assign a waste request to the nearest and least busy driver.
     */
    public function assign(WasteRequest $request)
    {
        $drivers = User::where('role', 'driver')
            ->withCount(['assignments' => function ($query) {
                $query->where('status', 'assigned');
            }])
            ->get();

        if ($drivers->isEmpty()) {
            return null;
        }

        $nearestDriver = null;
        $minDistance = PHP_INT_MAX;

        foreach ($drivers as $driver) {
            if (!$driver->location) continue;

            $distance = $this->calculateDistance(
                $request->latitude,
                $request->longitude,
                $driver->location->latitude,
                $driver->location->longitude
            );

            // Factor in workload: every active assignment adds a "virtual" distance of 2km
            $weightedDistance = $distance + ($driver->assignments_count * 2);

            if ($weightedDistance < $minDistance) {
                $minDistance = $weightedDistance;
                $nearestDriver = $driver;
            }
        }

        if ($nearestDriver) {
            $assignment = Assignment::create([
                'waste_request_id' => $request->id,
                'driver_id' => $nearestDriver->id,
                'status' => 'assigned',
                'assigned_at' => now(),
            ]);

            $request->update(['status' => 'assigned']);

            return $assignment;
        }

        return null;
    }

    /**
     * Haversine formula to calculate distance between two points in km.
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; // km

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
