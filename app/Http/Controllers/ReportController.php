<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;

class ReportController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        // Get all incidents for the user
        $incidents = Incident::where('reported_by', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($incident) {
                return [
                    'id' => 'INC-' . str_pad($incident->id, 5, '0', STR_PAD_LEFT),
                    'type' => 'incident',
                    'title' => $incident->incident_type,
                    'description' => $incident->description,
                    'location' => $incident->location,
                    'date' => $incident->incident_date ? \Carbon\Carbon::parse($incident->incident_date)->format('M d, Y') : 'N/A',
                    'status' => $incident->status ?? 'pending',
                ];
            });

        // Get all lost & found items for the user
        $lostFounds = LostFound::where('reported_by', $userId)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => 'LF-' . str_pad($item->id, 5, '0', STR_PAD_LEFT),
                    'type' => 'lostfound',
                    'title' => $item->item_name,
                    'description' => $item->description,
                    'location' => $item->location,
                    'date' => $item->created_at->format('M d, Y'),
                    'status' => $item->item_status ?? 'lost',
                ];
            });

        // Merge and sort by date (newest first)
        $allReports = collect($incidents)
            ->merge($lostFounds)
            ->sortByDesc('date')
            ->values();

        return view('reports.index', compact('allReports'));
    }
}