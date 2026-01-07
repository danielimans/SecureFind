<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LostFound;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {

        return view('dashboard');
    }
}
