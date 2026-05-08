<?php

namespace App\Http\Controllers;

use App\Models\DriverLocation;
use App\Models\User;
use Illuminate\Http\Request;

class TrackingController extends Controller
{
    /**
     * Get location of a specific driver.
     */
    public function getDriverLocation($driverId)
    {
        $location = DriverLocation::where('driver_id', $driverId)->first();
        
        if (!$location) {
            return response()->json(['error' => 'Location not found'], 404);
        }

        return response()->json([
            'lat' => $location->latitude,
            'lng' => $location->longitude,
            'updated_at' => $location->updated_at->diffForHumans()
        ]);
    }

    /**
     * Get locations of all active drivers (Admin view).
     */
    public function getAllDrivers()
    {
        $locations = DriverLocation::with('driver:id,name')->get();
        
        return response()->json($locations->map(function($loc) {
            return [
                'driver_id' => $loc->driver_id,
                'name' => $loc->driver->name,
                'lat' => $loc->latitude,
                'lng' => $loc->longitude,
            ];
        }));
    }

    /**
     * Update current driver's location (Simulation).
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = DriverLocation::updateOrCreate(
            ['driver_id' => auth()->id()],
            [
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]
        );

        return response()->json(['success' => true]);
    }
}
