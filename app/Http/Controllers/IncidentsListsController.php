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
    public function index(Request $request)
    {
        // Get incidents from last 30 days
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        
        $query = Incident::where('incident_date', '>=', $thirtyDaysAgo);

        if ($request->filled('type') && $request->type !== 'All') {
            if ($request->type === 'Other') {
                $query->where('incident_type', 'Other');
            } else {
                $query->where(function ($q) use ($request) {
                    $q->where('incident_type', $request->type)
                    ->orWhere('custom_incident_type', $request->type);
                });
            }
        }

        if ($request->filled('status') && $request->status !== 'All') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('description', 'like', '%' . $request->search . '%')
                ->orWhere('location', 'like', '%' . $request->search . '%')
                ->orWhere('incident_type', 'like', '%' . $request->search . '%')
                ->orWhere('custom_incident_type', 'like', '%' . $request->search . '%');
            });
        }

        $incidents = $query->orderBy('incident_date', 'desc')
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