<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Booking;

class DashboardV1Controller extends Controller
{
    /**
     * Display patient dashboard (v1).
     * For users with permission: view.dashboard.v1
     */
    public function index()
    {
        $user = auth()->user();
        $today = today();

        $activeBooking = Booking::where("user_id", $user->id)
            ->whereDate("booking_date", $today)
            ->whereIn("status", ["booking", "menunggu", "berlangsung"])
            ->orderBy("queue_number")
            ->first();

        $activeBookingCount = Booking::where("user_id", $user->id)
            ->whereIn("status", ["booking", "menunggu", "berlangsung"])
            ->count();

        $totalVisits = Booking::where("user_id", $user->id)
            ->where("status", "selesai")
            ->count();

        $servingNow = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "berlangsung")
            ->first();

        $waitingQueue = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "menunggu")
            ->orderBy("queue_number")
            ->get();

        $queueAhead = $activeBooking
            ? Booking::whereDate("booking_date", $today)
                ->whereIn("status", ["menunggu", "berlangsung"])
                ->where("queue_number", "<", $activeBooking->queue_number)
                ->count()
            : null;

        $upcomingQueue = $waitingQueue->take(5);

        return view("dashboard.v1", [
            "user" => $user,
            "activeBooking" => $activeBooking,
            "activeBookingCount" => $activeBookingCount,
            "totalVisits" => $totalVisits,
            "servingNow" => $servingNow,
            "upcomingQueue" => $upcomingQueue,
            "queueAhead" => $queueAhead,
        ]);
    }
}
