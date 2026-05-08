<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WasteCategory;
use App\Models\WasteRequest;
use Illuminate\Support\Facades\Auth;

class CitizenController extends Controller
{
    public function index()
    {
        $categories = WasteCategory::all();
        $requests = WasteRequest::where('user_id', Auth::id())->latest()->get();
        return view('citizen.dashboard', compact('categories', 'requests'));
    }

    public function storeRequest(Request $request)
    {
        $request->validate([
            'waste_category_id' => 'required|exists:waste_categories,id',
            'address' => 'required|string|max:255',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'image_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'pickup_date' => 'required|date|after_or_equal:today',
            'time_slot' => 'required|string',
        ]);

        $imagePath = null;
        if ($request->hasFile('image_proof')) {
            $imagePath = $request->file('image_proof')->store('waste_proofs', 'public');
        }

        $wasteRequest = WasteRequest::create([
            'user_id' => Auth::id(),
            'waste_category_id' => $request->waste_category_id,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'pending',
            'image_proof' => $imagePath,
        ]);

        // Create Schedule
        \App\Models\Schedule::create([
            'waste_request_id' => $wasteRequest->id,
            'pickup_date' => $request->pickup_date,
            'time_slot' => $request->time_slot,
        ]);

        // Generate QR Code Entry
        \App\Models\QrCode::create([
            'waste_request_id' => $wasteRequest->id,
            'code' => 'WMR-' . $wasteRequest->id . '-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(6)),
        ]);

        return back()->with('success', 'Waste pickup request scheduled successfully! QR Code generated.');
    }

    public function trackDriver($driver_id)
    {
        $driver = \App\Models\User::findOrFail($driver_id);
        return view('citizen.track', compact('driver'));
    }
}
