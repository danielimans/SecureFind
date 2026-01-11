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
       $lostFound = LostFound::findOrFail($id);
       return view('lostfound.show', compact('lostFound'));
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
            'item_name' => 'required',
            'item_category' => 'required',
            'location' => 'required',
            'description' => 'required',
            'date' => 'required',
            'time' => 'nullable',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096'
        ]);

        // Combine date and time
        $time = $request->time ?? '00:00';
        $validated['event_datetime'] = $request->date . ' ' . $time;

        // Status + user
        $validated['item_status'] = $request->status;
        $validated['reported_by'] = Auth::id();

        /* ===== SAVE IMAGE ===== */
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('lostfound', $filename, 'public');
            $validated['image'] = $path;   // <── CRITICAL
        }

        LostFound::create($validated);
        }
}