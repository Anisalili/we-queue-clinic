<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardV3Controller extends Controller
{
    /**
     * Display owner dashboard (v3).
     * For users with permission: view.dashboard.v3
     */
    public function index()
    {
        // Get today's date
        $today = Carbon::today();

        // Total patients (all time) - menggunakan Spatie role
        $totalPatients = User::role("patient")->count();

        // Bookings this month
        $bookingsThisMonth = Booking::whereBetween("booking_date", [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])->count();

        // BPJS count this month
        $bpjsCount = Booking::whereBetween("booking_date", [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])
            ->where("patient_category", "bpjs")
            ->count();

        // Umum count this month
        $umumCount = Booking::whereBetween("booking_date", [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth(),
        ])
            ->where("patient_category", "umum")
            ->count();

        // Today's schedule
        $todaySchedule = Booking::getScheduleForDate($today);
        $todaySlots = [
            "used" => Booking::byDate($today)
                ->whereNotIn("status", ["batal"])
                ->count(),
            "max" => $todaySchedule["max_slots"] ?? 0,
        ];

        // Weekly stats (last 7 days)
        $weeklyStats = Booking::whereBetween("booking_date", [
            Carbon::now()->subDays(7),
            Carbon::now(),
        ])
            ->selectRaw("DATE(booking_date) as date, COUNT(*) as total")
            ->groupBy("date")
            ->orderBy("date", "asc")
            ->get();

        // Recent bookings (today)
        $todayBookings = Booking::with("user")
            ->byDate($today)
            ->orderBy("queue_number", "asc")
            ->take(10)
            ->get();

        return view("dashboard.v3", [
            "user" => auth()->user(),
            "total_patients" => $totalPatients,
            "bookings_this_month" => $bookingsThisMonth,
            "bpjs_count" => $bpjsCount,
            "umum_count" => $umumCount,
            "today_schedule" => $todaySchedule,
            "today_slots" => $todaySlots,
            "weekly_stats" => $weeklyStats,
            "today_bookings" => $todayBookings,
        ]);
    }
}
