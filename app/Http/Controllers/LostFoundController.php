<?php

namespace App\Http\Controllers;

use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;

class LostFoundController extends Controller
{
    /* =====================
       CREATE REPORT FORM
    ===================== */
    public function create()
    {
        return view('lostfound.report');
    }

    /* =====================
       STORE REPORT
    ===================== */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'status'      => 'required|in:lost,found',
            'item_name'   => 'required|string|max:255',
            'category'    => 'nullable|string|max:255',
            'location'    => 'required|string|max:255',
            'description' => 'required|min:20',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'date'        => 'required|date',
            'time'        => 'nullable|date_format:H:i',
        ]);

        $data = [
            'item_name'     => $validated['item_name'],
            'item_category' => $validated['category'] ?? null,
            'item_status'   => $validated['status'],
            'location'      => $validated['location'],
            'description'   => $validated['description'],
            'reported_by'   => Auth::id(),
            'image'         => null,
        ];

        /* IMAGE UPLOAD */
        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            try {
                $file = $request->file('image');
                $filename = now()->timestamp . '_' . bin2hex(random_bytes(5)) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('lost-found', $filename, 'public');
                $data['image'] = $path;
            } catch (\Exception $e) {
                Log::error('Image upload failed: ' . $e->getMessage());
            }
        }

        /* DATE + TIME */
        $time = $validated['time'] ?? '00:00';
        $data['event_datetime'] = $validated['date'] . ' ' . $time . ':00';

        try {
            LostFound::create($data);

            return redirect()
                ->route('reports.index')
                ->with('success', 'Lost & Found report submitted successfully');
        } catch (\Exception $e) {
            Log::error('Create failed: ' . $e->getMessage());

            if ($data['image']) {
                Storage::disk('public')->delete($data['image']);
            }

            return back()
                ->withInput()
                ->with('error', 'Failed to submit report.');
        }
    }

    /* =====================
       MY REPORTS LIST
    ===================== */
    public function index()
    {
        $reports = LostFound::with('reportedBy')
            ->where('reported_by', Auth::id())
            ->latest()
            ->get();

        return view('lostfound.index', compact('reports'));
    }

    /* =====================
       VIEW SINGLE REPORT
    ===================== */
    public function show(LostFound $lostFound)
    {
        if (!Gate::allows('view', $lostFound)) {
            abort(403, 'Unauthorized');
        }

        return view('lostfound.show', compact('lostFound'));
    }

    /* =====================
       DELETE REPORT (FIX)
    ===================== */
    public function destroy(LostFound $lostfound)
    {
        // Ownership protection
        if ($lostfound->reported_by !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Delete image if exists
        if ($lostfound->image) {
            Storage::disk('public')->delete($lostfound->image);
        }

        $lostfound->delete();

        return redirect()
            ->route('reports.index')
            ->with('success', 'Report deleted successfully');
    }
}
