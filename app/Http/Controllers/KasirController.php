<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class KasirController extends Controller
{
    public function index()
    {
        return view('kasir.dashboard');
    }
}

