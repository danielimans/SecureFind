<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Active incidents
        $activeIncidents = Incident::where('status', 'active')->count();

        // Lost items
        $lostItems = LostFound::where('item_status', 'lost')->count();

        // My reports
        $myReportsTotal =
            Incident::where('reported_by', Auth::id())->count()
            + LostFound::where('reported_by', Auth::id())->count();

        // Pending reports
        $pendingReports =
            Incident::where('reported_by', Auth::id())
                ->where('status', 'pending')
                ->count();

        // Recent incidents (latest 4)
        $recentIncidents = Incident::orderBy('incident_date', 'desc')
            ->limit(4)
            ->get();

        // Recent lost & found items (latest 3)
        $recentLostFound = LostFound::orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        return view('dashboard', compact(
            'activeIncidents',
            'lostItems',
            'myReportsTotal',
            'pendingReports',
            'recentIncidents',
            'recentLostFound'
        ));
    }
}