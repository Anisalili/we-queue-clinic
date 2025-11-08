<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardV3Controller extends Controller
{
    /**
     * Display owner dashboard (v3).
     * For users with permission: view.dashboard.v3
     */
    public function index()
    {
        // Owner dashboard: show analytics, reports, system overview
        return view('dashboard.v3', [
            'user' => auth()->user(),
            // TODO: Add owner-specific data
            // - Analytics & charts
            // - Monthly statistics
            // - System health
            // - BPJS vs Umum breakdown
        ]);
    }
}
