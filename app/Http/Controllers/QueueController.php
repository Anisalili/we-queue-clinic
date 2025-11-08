<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class QueueController extends Controller
{
    /**
     * Display the queue dashboard (Admin/Owner)
     */
    public function index()
    {
        // Get today's queues grouped by status
        $today = today();

        // Waiting queue (status: menunggu) - ordered by queue number
        $waitingQueue = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "menunggu")
            ->orderBy("queue_number", "asc")
            ->get();

        // Currently being served (status: berlangsung)
        $servingNow = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "berlangsung")
            ->first();

        // Not yet checked in (status: booking)
        $notCheckedIn = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "booking")
            ->orderBy("queue_number", "asc")
            ->get();

        // Completed today (status: selesai)
        $completed = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "selesai")
            ->orderBy("queue_number", "asc")
            ->get();

        // Cancelled today (status: batal)
        $cancelled = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "batal")
            ->orderBy("queue_number", "asc")
            ->get();

        // Statistics
        $stats = [
            "total_today" => Booking::whereDate("booking_date", $today)
                ->whereNotIn("status", ["batal"])
                ->count(),
            "waiting" => $waitingQueue->count(),
            "serving" => $servingNow ? 1 : 0,
            "completed" => $completed->count(),
            "cancelled" => $cancelled->count(),
            "not_checked_in" => $notCheckedIn->count(),
            "bpjs_today" => Booking::whereDate("booking_date", $today)
                ->where("patient_category", "bpjs")
                ->whereNotIn("status", ["batal"])
                ->count(),
            "umum_today" => Booking::whereDate("booking_date", $today)
                ->where("patient_category", "umum")
                ->whereNotIn("status", ["batal"])
                ->count(),
        ];

        // Get next queue number (if no one is being served, get first from waiting)
        $nextQueue = null;
        if (!$servingNow && $waitingQueue->count() > 0) {
            $nextQueue = $waitingQueue->first();
        }

        return view(
            "queue.index",
            compact(
                "waitingQueue",
                "servingNow",
                "notCheckedIn",
                "completed",
                "cancelled",
                "stats",
                "nextQueue",
            ),
        );
    }

    /**
     * Public queue display (for TV screen in waiting room)
     */
    public function display()
    {
        $today = today();

        // Currently being served
        $servingNow = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "berlangsung")
            ->first();

        // Next 5 in queue
        $upcomingQueue = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "menunggu")
            ->orderBy("queue_number", "asc")
            ->limit(5)
            ->get();

        // Recently completed (last 3)
        $recentlyCompleted = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "selesai")
            ->orderBy("service_end_time", "desc")
            ->limit(3)
            ->get();

        return view(
            "queue.display",
            compact("servingNow", "upcomingQueue", "recentlyCompleted"),
        );
    }

    /**
     * Call next patient (change status from menunggu to berlangsung)
     */
    public function callNext(Request $request)
    {
        // Check if someone is already being served
        $currentlyServing = Booking::whereDate("booking_date", today())
            ->where("status", "berlangsung")
            ->first();

        if ($currentlyServing) {
            return back()->with(
                "error",
                "Masih ada pasien yang sedang dilayani! Selesaikan pasien tersebut terlebih dahulu.",
            );
        }

        // Get next patient from waiting queue
        $nextPatient = Booking::whereDate("booking_date", today())
            ->where("status", "menunggu")
            ->orderBy("queue_number", "asc")
            ->first();

        if (!$nextPatient) {
            return back()->with("error", "Tidak ada pasien dalam antrian!");
        }

        // Start service
        $nextPatient->update([
            "status" => "berlangsung",
            "service_start_time" => now(),
        ]);

        return back()->with(
            "success",
            "Pasien dipanggil! Nomor Antrian: " .
                $nextPatient->formatted_queue_number .
                " - " .
                $nextPatient->user->name,
        );
    }

    /**
     * Call specific patient
     */
    public function callSpecific(Booking $booking)
    {
        if ($booking->status !== "menunggu") {
            return back()->with(
                "error",
                "Pasien ini tidak dalam status menunggu!",
            );
        }

        // Check if someone is already being served
        $currentlyServing = Booking::whereDate("booking_date", today())
            ->where("status", "berlangsung")
            ->first();

        if ($currentlyServing) {
            return back()->with(
                "error",
                "Masih ada pasien yang sedang dilayani! Selesaikan pasien tersebut terlebih dahulu.",
            );
        }

        // Start service
        $booking->update([
            "status" => "berlangsung",
            "service_start_time" => now(),
        ]);

        return back()->with(
            "success",
            "Pasien dipanggil! Nomor Antrian: " .
                $booking->formatted_queue_number .
                " - " .
                $booking->user->name,
        );
    }

    /**
     * Skip patient (keep in queue but mark with note)
     */
    public function skip(Booking $booking, Request $request)
    {
        if ($booking->status !== "menunggu") {
            return back()->with(
                "error",
                "Pasien ini tidak dalam status menunggu!",
            );
        }

        $validated = $request->validate([
            "skip_reason" => "nullable|string|max:500",
        ]);

        // Add note to booking
        $booking->update([
            "notes" =>
                "Dilewati: " . ($validated["skip_reason"] ?? "Belum hadir"),
        ]);

        return back()->with(
            "info",
            "Pasien dilewati: " . $booking->formatted_queue_number,
        );
    }

    /**
     * Get queue data for AJAX refresh
     */
    public function getData()
    {
        $today = today();

        $waitingQueue = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "menunggu")
            ->orderBy("queue_number", "asc")
            ->get();

        $servingNow = Booking::with("user")
            ->whereDate("booking_date", $today)
            ->where("status", "berlangsung")
            ->first();

        $stats = [
            "waiting" => $waitingQueue->count(),
            "serving" => $servingNow ? 1 : 0,
            "completed" => Booking::whereDate("booking_date", $today)
                ->where("status", "selesai")
                ->count(),
        ];

        return response()->json([
            "serving_now" => $servingNow
                ? [
                    "id" => $servingNow->id,
                    "queue_number" => $servingNow->formatted_queue_number,
                    "patient_name" => $servingNow->user->name,
                    "category" => $servingNow->patient_category,
                    "start_time" => $servingNow->service_start_time->format(
                        "H:i",
                    ),
                ]
                : null,
            "waiting_count" => $waitingQueue->count(),
            "next_queue" => $waitingQueue->first()
                ? $waitingQueue->first()->formatted_queue_number
                : null,
            "stats" => $stats,
        ]);
    }
}
