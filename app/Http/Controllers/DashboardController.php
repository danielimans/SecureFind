<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Incident;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Active incidents - count incidents that are NOT resolved
        // This includes 'active', 'pending', and any other status except 'resolved'
        $activeIncidents = Incident::where('status', '!=', 'resolved')->count();
        
        // Active incidents from last 7 days
        $activeIncidentsLastWeek = Incident::where('status', '!=', 'resolved')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Lost items - current count
        $lostItems = LostFound::where('item_status', 'lost')->count();
        
        // Lost items from last 7 days
        $lostItemsLastWeek = LostFound::where('item_status', 'lost')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

        // Found items - current count
        $foundItems = LostFound::where('item_status', 'found')->count();
        
        // Found items from last 7 days
        $foundItemsLastWeek = LostFound::where('item_status', 'found')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();

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

        // Calculate trends (compare this week vs last week)
        $activeIncidentsTrend = $activeIncidentsLastWeek > 0 ? 'up' : 'down';
        $lostItemsTrend = $lostItemsLastWeek > 0 ? 'up' : 'down';
        $foundItemsTrend = $foundItemsLastWeek > 0 ? 'up' : 'down';

        return view('dashboard', compact(
            'activeIncidents',
            'activeIncidentsTrend',
            'activeIncidentsLastWeek',
            'lostItems',
            'lostItemsTrend',
            'lostItemsLastWeek',
            'foundItems',
            'foundItemsTrend',
            'foundItemsLastWeek',
            'myReportsTotal',
            'pendingReports',
            'recentIncidents',
            'recentLostFound'
        ));
    }
}