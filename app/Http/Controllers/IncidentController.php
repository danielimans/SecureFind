<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Incident;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function create()
    {
        return view('incidents.report');
    }

    /**
     * Store incident into database
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'incident_type' => 'required|string|max:255',
            'location'      => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'nullable',
            'description'   => 'required|min:30',
            'evidence'      => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Combine DATE + TIME into one datetime
        $time = $request->incident_time ?? '00:00';
        $validated['incident_date'] =
            $request->incident_date . ' ' . $time . ':00';

        // Upload evidence
        if ($request->hasFile('evidence')) {
            $validated['evidence'] =
                $request->file('evidence')->store('incidents', 'public');
        }

        // Add system fields
        $validated['reported_by'] = Auth::id();
        $validated['status'] = 'pending';

        Incident::create($validated);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Incident reported successfully.');
    }
}
