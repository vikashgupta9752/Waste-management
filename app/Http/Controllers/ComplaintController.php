<?php

namespace App\Http\Controllers;

use App\Models\Complaint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplaintController extends Controller
{
    // Citizen side
    public function index()
    {
        $complaints = Complaint::where('user_id', Auth::id())->latest()->get();
        return view('citizen.complaints.index', compact('complaints'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('complaints', 'public');
        }

        Complaint::create([
            'user_id' => Auth::id(),
            'subject' => $request->subject,
            'description' => $request->description,
            'image_path' => $imagePath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return back()->with('success', 'Complaint reported successfully. Our team will review it soon.');
    }

    // Admin side
    public function adminIndex()
    {
        $complaints = Complaint::with('user')->latest()->get();
        return view('admin.complaints.index', compact('complaints'));
    }

    public function update(Request $request, Complaint $complaint)
    {
        $request->validate([
            'status' => 'required|in:resolved,rejected',
            'admin_comment' => 'nullable|string',
        ]);

        $complaint->update([
            'status' => $request->status,
            'admin_comment' => $request->admin_comment,
        ]);

        // Notify user
        \App\Models\AppNotification::create([
            'user_id' => $complaint->user_id,
            'title' => 'Complaint Update',
            'message' => "Your complaint '{$complaint->subject}' has been marked as {$request->status}.",
            'type' => $request->status == 'resolved' ? 'success' : 'danger',
        ]);

        return back()->with('success', 'Complaint status updated.');
    }
}
