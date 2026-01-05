<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LostFoundController extends Controller
{
    public function create()
    {
        return view('lostfound.index');
    }
}
