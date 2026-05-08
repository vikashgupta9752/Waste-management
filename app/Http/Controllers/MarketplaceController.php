<?php

namespace App\Http\Controllers;

use App\Models\MarketplaceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MarketplaceController extends Controller
{
    public function index()
    {
        $items = MarketplaceItem::with('user')->where('status', 'available')->latest()->get();
        return view('citizen.marketplace.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('marketplace', 'public');
        }

        MarketplaceItem::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'category' => $request->category,
            'image_path' => $imagePath,
        ]);

        return back()->with('success', 'Item listed for sale successfully!');
    }

    public function myItems()
    {
        $items = MarketplaceItem::where('user_id', Auth::id())->latest()->get();
        return view('citizen.marketplace.my-items', compact('items'));
    }
}
