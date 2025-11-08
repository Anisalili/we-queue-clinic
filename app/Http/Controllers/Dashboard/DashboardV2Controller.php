<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardV2Controller extends Controller
{
    /**
     * Display admin dashboard (v2).
     * For users with permission: view.dashboard.v2
     */
    public function index()
    {
        // Admin dashboard: show today's queue, quick actions
        return view('dashboard.v2', [
            'user' => auth()->user(),
            // TODO: Add admin-specific data
            // - Today's queue list
            // - Pending bookings
            // - Quick stats (total patients today, waiting, completed)
        ]);
    }
}
