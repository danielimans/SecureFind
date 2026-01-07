<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LostFoundController extends Controller
{
    public function create()
    {
        return view('lostfound.report');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'status' => 'required|in:lost,found',
            'item_name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|min:20',
            'image' => 'nullable|image|max:10240',
        ]);

        $data = [
            'item_name'     => $validated['item_name'],
            'item_category' => $validated['category'] ?? null,
            'item_status'   => $validated['status'],
            'location'      => $validated['location'],
            'description'   => $validated['description'],
            'reported_by'   => Auth::id(),
        ];

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('lost-found', 'public');
        }

        $time = $request->time ?? '00:00';

        $data['event_datetime'] =
            $request->date . ' ' . $time . ':00';


        LostFound::create($data);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Lost & Found report submitted successfully');
    }
}
