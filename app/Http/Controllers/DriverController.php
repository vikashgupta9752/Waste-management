<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;
use App\Models\WasteRequest;
use App\Http\Controllers\GamificationController;
use Illuminate\Support\Facades\Auth;

use App\Models\DriverLocation;

class DriverController extends Controller
{
    public function index()
    {
        $assignments = Assignment::with(['wasteRequest.wasteCategory', 'wasteRequest.user'])
            ->where('driver_id', Auth::id())
            ->latest()
            ->get();
            
        // Performance metrics
        $tasksToday = Assignment::where('driver_id', Auth::id())
            ->whereDate('updated_at', now())
            ->count();
        $efficiency = $tasksToday > 0 ? 100 : 0; // Simple mock efficiency
            
        return view('driver.dashboard', compact('assignments', 'tasksToday', 'efficiency'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'status' => 'required|in:on_the_way,collected,disposed',
            'image_proof' => 'nullable|image|max:2048',
        ]);

        $assignment = Assignment::find($request->assignment_id);
        
        $wasteRequest = $assignment->wasteRequest;
        $wasteRequest->status = $request->status;
        
        if ($request->hasFile('image_proof')) {
            $path = $request->file('image_proof')->store('proofs', 'public');
            $wasteRequest->image_proof = $path;
            $wasteRequest->verification_status = 'pending';
        }

        $wasteRequest->save();

        if ($request->status == 'disposed') {
            $assignment->status = 'completed';
            $assignment->save();
        }

        // Gamification: Award points to the citizen
        if ($request->status == 'collected') {
            GamificationController::awardPoints($wasteRequest->user, 10, 0.5);
        } elseif ($request->status == 'disposed') {
            GamificationController::awardPoints($wasteRequest->user, 20, 1.5);
        }

        return back()->with('success', 'Status updated to ' . ucfirst(str_replace('_', ' ', $request->status)));
    }

    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        DriverLocation::updateOrCreate(
            ['driver_id' => Auth::id()],
            ['latitude' => $request->latitude, 'longitude' => $request->longitude, 'updated_at' => now()]
        );

        return response()->json(['success' => true]);
    }

    public function getDriverLocation($driver_id)
    {
        $location = DriverLocation::where('driver_id', $driver_id)->first();
        return response()->json($location);
    }

    public function verifyQr(Request $request)
    {
        $request->validate([
            'waste_request_id' => 'required|exists:waste_requests,id',
            'qr_code' => 'required|string',
        ]);

        $qrEntry = \App\Models\QrCode::where('waste_request_id', $request->waste_request_id)
            ->where('code', $request->qr_code)
            ->first();

        if (!$qrEntry) {
            return response()->json(['success' => false, 'message' => 'Invalid QR Code. This code does not belong to this request.'], 422);
        }

        $wasteRequest = WasteRequest::find($request->waste_request_id);

        if ($wasteRequest->status == 'collected') {
            return response()->json(['success' => false, 'message' => 'This QR has already been scanned. Waste is already Collected.'], 422);
        }

        if ($wasteRequest->status == 'disposed') {
            return response()->json(['success' => false, 'message' => 'This request is already finished and Disposed.'], 422);
        }

        // Proceed to mark as collected
        $wasteRequest->status = 'collected';
        $wasteRequest->save();

        // Give points to user
        $user = $wasteRequest->user;
        $user->points += 50;
        $user->save();

        return response()->json(['success' => true, 'message' => 'QR Verified! Waste marked as Collected. +50 points awarded to citizen.']);
    }
}
