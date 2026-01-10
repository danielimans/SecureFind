<?php

namespace App\Http\Controllers;

use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ItemsListsController extends Controller
{
    /**
     * Display all lost & found items
     */
    public function index()
    {
        $items = LostFound::orderBy('created_at', 'desc')
            ->paginate(15);

        // Count by status
        $totalItems = LostFound::count();
        $lostCount = LostFound::where('item_status', 'lost')->count();
        $foundCount = LostFound::where('item_status', 'found')->count();

        return view('lostfound.items-list', compact(
            'items',
            'totalItems',
            'lostCount',
            'foundCount'
        ));
    }

    /**
     * Display a single lost & found item
     */
    public function show($id)
    {
        $item = LostFound::findOrFail($id);
        return view('lostfound.show', compact('item'));
    }

    /**
     * Show the form for creating a new lost & found item
     */
    public function create()
    {
        return view('lostfound.report');
    }

    /**
     * Store a newly created lost & found item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'date' => 'required|date',
            'time' => 'nullable',
            'description' => 'required|min:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:10240',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('lostfound', 'public');
            $validated['image'] = $path;
        }

        // Combine date and time
        $time = $request->time ?? '00:00';
        $validated['event_datetime'] = $request->date . ' ' . $time;

        // Add metadata
        $validated['item_status'] = $request->status;
        $validated['reported_by'] = Auth::id();

        LostFound::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Lost & Found item reported successfully.');
    }
}