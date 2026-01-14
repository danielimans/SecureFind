<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class IncidentController extends Controller
{
    /**
     * Show report form
     */
    public function create()
    {
        return view('incidents.report');
    }

    /**
     * Store incident
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $validated = $request->validate([
            'incident_type'        => 'required|string|max:255',
            'custom_incident_type' => 'nullable|string|max:255',

            // Map location
            'location'             => 'required|string|max:255',
            'latitude'             => 'required|numeric',
            'longitude'            => 'required|numeric',

            'incident_date'        => 'required|date',
            'incident_time'        => 'nullable',
            'description'          => 'required|min:30',

            'evidence'             => 'nullable|array',
            'evidence.*'           => 'file|mimes:jpg,jpeg,png,pdf|max:10240',
        ]);

        // Combine date + time
        $time = $request->incident_time ?? '00:00';

        $incidentDateTime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->incident_date . ' ' . $time,
            config('app.timezone')
        );

        // Prepare data for DB
        $data = [
            'incident_type'        => $request->incident_type,
            'custom_incident_type' => $request->incident_type === 'Other'
                                      ? $request->custom_incident_type
                                      : null,

            'location'      => $request->location,
            'latitude'      => $request->latitude,
            'longitude'     => $request->longitude,

            'incident_date' => $incidentDateTime,
            'description'   => $request->description,

            'reported_by'   => Auth::id(),
            'status'        => 'pending',
        ];

        // Handle evidence uploads
        if ($request->hasFile('evidence')) {
            $paths = [];
            foreach ($request->file('evidence') as $file) {
                $paths[] = $file->store('incidents', 'public');
            }
            $data['evidence'] = json_encode($paths);
        }

        // Save incident
        Incident::create($data);

        return redirect()
            ->route('reports.index')
            ->with('success', 'Incident reported successfully.');
    }

    /**
     * Edit incident
     */
    public function edit(Incident $incident)
    {
        if ($incident->reported_by !== Auth::id()) {
            abort(403);
        }

        return view('incidents.edit', compact('incident'));
    }

    /**
     * Update incident
     */
    public function update(Request $request, Incident $incident)
    {
        if ($incident->reported_by !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'incident_type' => 'required|string|max:255',
            'custom_incident_type' => 'nullable|string|max:255',
            'incident_location' => 'required|string|max:255',
            'incident_lat' => 'required|numeric',
            'incident_lng' => 'required|numeric',
            'incident_date' => 'required|date',
            'incident_time' => 'nullable',
            'description' => 'required|min:30',
        ]);

        $time = $request->incident_time ?? '00:00';
        $datetime = Carbon::createFromFormat(
            'Y-m-d H:i',
            $request->incident_date . ' ' . $time,
            config('app.timezone')
        );

        $incident->update([
            'incident_type' => $request->incident_type,
            'custom_incident_type' => $request->incident_type === 'Other'
                ? $request->custom_incident_type
                : null,
            'location' => $request->incident_location,
            'latitude' => $request->incident_lat,
            'longitude' => $request->incident_lng,
            'incident_date' => $datetime,
            'description' => $request->description
        ]);

        return redirect()
            ->route('incidents.show', $incident->id)
            ->with('success', 'Incident updated successfully.');
    }

    /**
     * Delete incident
     */
    public function destroy(Incident $incident)
    {
        if ($incident->reported_by !== Auth::id()) {
            abort(403);
        }

        $incident->delete();

        return redirect()
            ->route('reports.index')
            ->with('success', 'Incident deleted successfully.');
    }
}
