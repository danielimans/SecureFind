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
            'evidence'      => 'nullable|array',
            'evidence.*'    => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Combine DATE + TIME into one datetime
        $time = $request->incident_time ?? '00:00';
        $validated['incident_date'] =
            $request->incident_date . ' ' . $time . ':00';

        // Handle multiple evidence files
        $evidencePaths = [];
        if ($request->hasFile('evidence')) {
            foreach ($request->file('evidence') as $file) {
                $path = $file->store('incidents', 'public');
                $evidencePaths[] = $path;
            }
            // Store as JSON array
            $validated['evidence'] = json_encode($evidencePaths);
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