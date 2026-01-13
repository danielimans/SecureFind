<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Incident;
use Illuminate\Http\Request;
use Carbon\Carbon;

class IncidentController extends Controller
{
    /**
     * Show the form for creating a new incident
     */
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
            'custom_incident_type' => 'nullable|string|max:255',
            'location'      => 'required|string|max:255',
            'incident_date' => 'required|date',
            'incident_time' => 'nullable',
            'description'   => 'required|min:30',
            'evidence'      => 'nullable|array',
            'evidence.*'    => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Combine DATE + TIME into one datetime
        $time = $request->incident_time ?? '00:00';
        
        // Create Carbon instance - this will automatically use the app timezone (Asia/Kuala_Lumpur)
        $incidentDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->incident_date . ' ' . $time
        );
        
        // Store the datetime - Laravel will automatically convert to UTC for database storage
        $validated['incident_date'] = $incidentDateTime;

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

        if ($validated['incident_type'] === 'Other') {
            $validated['custom_incident_type'] = $request->custom_incident_type;
        } else {
            $validated['custom_incident_type'] = null;
        }

        Incident::create($validated);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Incident reported successfully.');
    }
}