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
        $activeIncidents = Incident::where('status', 'active')->count();
        $lostItems = LostFound::where('item_status', 'lost')->count();
        $myReportsTotal = Incident::where('reported_by', Auth::id())->count()
            + LostFound::where('reported_by', Auth::id())->count();
        $pendingReports = Incident::where('reported_by', Auth::id())
            ->where('status', 'pending')
            ->count();
        
        // Add this
        $recentIncidents = Incident::where('status', 'active')
            ->latest()
            ->limit(4)
            ->get();

        return view('dashboard', compact(
            'activeIncidents',
            'lostItems',
            'myReportsTotal',
            'pendingReports',
            'recentIncidents'
        ));
    }
}
