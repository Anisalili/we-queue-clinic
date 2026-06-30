<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class LandingController extends Controller
{
    public function index()
    {
        $timezone = config("app.timezone");
        $today = Carbon::today($timezone);
        $now = Carbon::now($timezone);

        // Determine which date to show for availability (if sesi hari ini sudah lewat, pakai besok)
        $scheduleToday = Booking::getScheduleForDate($today);
        $endTimeToday = $scheduleToday["end_time"] ?? null;
        $sessionEnded =
            ($scheduleToday["is_closed"] ?? false) ||
            ($endTimeToday &&
                $now->greaterThan(
                    Carbon::parse($endTimeToday, $timezone)->setDateFrom(
                        $today,
                    ),
                ));

        $availabilityDate = $sessionEnded
            ? $today->copy()->addDay()
            : $today->copy();
        $schedule = Booking::getScheduleForDate($availabilityDate);

        $stats = [
            "total_bookings" => Booking::whereDate(
                "booking_date",
                $today,
            )->count(),
            "booking_today" => Booking::whereDate("booking_date", $today)
                ->whereIn("status", ["booking", "menunggu", "berlangsung"])
                ->count(),
            "waiting_count" => Booking::whereDate("booking_date", $today)
                ->where("status", "menunggu")
                ->count(),
            "slots_available" => Booking::getAvailableSlots($availabilityDate),
        ];

        $slotsLabel = $sessionEnded
            ? "Kuota Tersisa Besok"
            : "Kuota Tersisa Hari Ini";

        $scheduleInfo =
            "Jadwal " . $availabilityDate->translatedFormat("l, d F Y") . ": ";
        if ($schedule["is_closed"] ?? false) {
            $scheduleInfo .= $schedule["reason"] ?? "Klinik tutup";
        } elseif (
            !empty($schedule["start_time"]) &&
            !empty($schedule["end_time"])
        ) {
            $scheduleInfo .=
                Carbon::parse($schedule["start_time"], $timezone)->format(
                    "H:i",
                ) .
                " - " .
                Carbon::parse($schedule["end_time"], $timezone)->format("H:i") .
                " WITA";
        } else {
            $scheduleInfo .= "Ikuti jadwal klinik.";
        }

        return view("landing.index", [
            "stats" => $stats,
            "scheduleInfo" => $scheduleInfo,
            "slotsLabel" => $slotsLabel,
            "today" => $today->translatedFormat("l, d F Y"),
        ]);
    }
}
