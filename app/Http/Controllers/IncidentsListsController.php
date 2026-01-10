<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use Carbon\Carbon;
use Illuminate\Http\Request;

class IncidentsListsController extends Controller
{
    /**
     * Display all incidents from last 30 days (ALL USERS)
     */
    public function index()
    {
        // Get incidents from last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $incidents = Incident::where('incident_date', '>=', $thirtyDaysAgo)
            ->orderBy('incident_date', 'desc')
            ->with('reporter')
            ->paginate(15);

        // Count by status
        $totalIncidents = Incident::where('incident_date', '>=', $thirtyDaysAgo)->count();
        $activeCount = Incident::where('incident_date', '>=', $thirtyDaysAgo)
            ->where('status', 'active')
            ->count();
        $pendingCount = Incident::where('incident_date', '>=', $thirtyDaysAgo)
            ->where('status', 'pending')
            ->count();
        $resolvedCount = Incident::where('incident_date', '>=', $thirtyDaysAgo)
            ->where('status', 'resolved')
            ->count();

        return view('incidents.incidents-lists', compact(
            'incidents',
            'totalIncidents',
            'activeCount',
            'pendingCount',
            'resolvedCount'
        ));
    }

    /**
     * Display a single incident
     */
    public function show($id)
    {
        $incident = Incident::with('reporter')->findOrFail($id);
        return view('incidents.show', compact('incident'));
    }
}