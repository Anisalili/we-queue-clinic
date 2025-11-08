<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardV1Controller extends Controller
{
    /**
     * Display patient dashboard (v1).
     * For users with permission: view.dashboard.v1
     */
    public function index()
    {
        // Patient dashboard: show own bookings, queue status
        return view('dashboard.v1', [
            'user' => auth()->user(),
            // TODO: Add patient-specific data
            // - Active bookings
            // - Queue position
            // - Booking history
        ]);
    }
}
