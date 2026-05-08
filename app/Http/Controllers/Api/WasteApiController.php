<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WasteRequest;
use App\Models\Bin;
use App\Models\DriverLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WasteApiController extends Controller
{
    /**
     * GET /api/requests
     */
    public function getRequests()
    {
        $requests = WasteRequest::with(['user', 'wasteCategory'])->latest()->get();
        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    /**
     * GET /api/driver-locations
     */
    public function getDriverLocations()
    {
        $locations = DriverLocation::with('driver')->get();
        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * GET /api/bin-status
     */
    public function getBinStatus()
    {
        $bins = Bin::all();
        return response()->json([
            'success' => true,
            'data' => $bins
        ]);
    }

    /**
     * POST /api/create-request
     */
    public function createRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'waste_category_id' => 'required|exists:waste_categories,id',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $wasteRequest = WasteRequest::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Waste request created successfully via API',
            'data' => $wasteRequest
        ], 201);
    }
}
