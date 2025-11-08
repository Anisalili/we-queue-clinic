<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of all bookings (Admin/Owner)
     */
    public function index(Request $request)
    {
        $query = Booking::with("user")
            ->orderBy("booking_date", "desc")
            ->orderBy("queue_number", "asc");

        // Filter by date
        if ($request->filled("date")) {
            $query->whereDate("booking_date", $request->date);
        } else {
            // Default: show today's bookings
            $query->whereDate("booking_date", today());
        }

        // Filter by status
        if ($request->filled("status")) {
            $query->where("status", $request->status);
        }

        // Filter by category
        if ($request->filled("category")) {
            $query->where("patient_category", $request->category);
        }

        // Search by patient name
        if ($request->filled("search")) {
            $query->whereHas("user", function ($q) use ($request) {
                $q->where("name", "like", "%" . $request->search . "%");
            });
        }

        $bookings = $query->paginate(20);

        // Statistics for today
        $stats = [
            "total" => Booking::today()->count(),
            "booking" => Booking::today()->byStatus("booking")->count(),
            "menunggu" => Booking::today()->byStatus("menunggu")->count(),
            "berlangsung" => Booking::today()->byStatus("berlangsung")->count(),
            "selesai" => Booking::today()->byStatus("selesai")->count(),
            "batal" => Booking::today()->byStatus("batal")->count(),
            "bpjs" => Booking::today()
                ->bpjs()
                ->whereNotIn("status", ["batal"])
                ->count(),
            "umum" => Booking::today()
                ->umum()
                ->whereNotIn("status", ["batal"])
                ->count(),
        ];

        return view("booking.index", compact("bookings", "stats"));
    }

    /**
     * Show the form for creating a new booking (Patient - Online Booking)
     */
    public function create()
    {
        // Check if user already has active booking
        $activeBooking = Booking::where("user_id", auth()->id())
            ->whereIn("status", ["booking", "menunggu"])
            ->first();

        if ($activeBooking) {
            return redirect()
                ->route("booking.mine")
                ->with(
                    "error",
                    "Anda masih memiliki booking aktif. Selesaikan booking tersebut terlebih dahulu.",
                );
        }

        // Get available dates (next 7 days)
        $availableDates = $this->getAvailableDates();

        return view("booking.create", compact("availableDates"));
    }

    /**
     * Store a newly created booking (Patient - Online Booking)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "booking_date" => "required|date|after_or_equal:today",
            "patient_category" => "required|in:bpjs,umum",
        ]);

        // Check if user already has active booking
        $activeBooking = Booking::where("user_id", auth()->id())
            ->whereIn("status", ["booking", "menunggu"])
            ->first();

        if ($activeBooking) {
            return back()
                ->with("error", "Anda masih memiliki booking aktif!")
                ->withInput();
        }

        // Validate booking date
        $canBook = Booking::canBookDate(
            $validated["booking_date"],
            auth()->id(),
        );

        if (!$canBook["can_book"]) {
            return back()->with("error", $canBook["reason"])->withInput();
        }

        // Get next queue number
        $queueNumber = Booking::getNextQueueNumber($validated["booking_date"]);

        // Create booking
        $booking = Booking::create([
            "user_id" => auth()->id(),
            "booking_date" => $validated["booking_date"],
            "queue_number" => $queueNumber,
            "patient_category" => $validated["patient_category"],
            "status" => "booking",
            "booking_type" => "online",
        ]);

        return redirect()
            ->route("booking.show", $booking)
            ->with(
                "success",
                "Booking berhasil dibuat! Nomor antrian Anda: " .
                    $booking->formatted_queue_number,
            );
    }

    /**
     * Display the specified booking
     */
    public function show(Booking $booking)
    {
        // Authorization check
        if (
            auth()->user()->hasRole("patient") &&
            $booking->user_id !== auth()->id()
        ) {
            abort(403, "Unauthorized");
        }

        $booking->load("user");

        return view("booking.show", compact("booking"));
    }

    /**
     * Show user's bookings (Patient - My Bookings)
     */
    public function mine()
    {
        $activeBookings = Booking::where("user_id", auth()->id())
            ->whereIn("status", ["booking", "menunggu", "berlangsung"])
            ->orderBy("booking_date", "asc")
            ->get();

        $historyBookings = Booking::where("user_id", auth()->id())
            ->whereIn("status", ["selesai", "batal"])
            ->orderBy("booking_date", "desc")
            ->paginate(10);

        return view(
            "booking.mine",
            compact("activeBookings", "historyBookings"),
        );
    }

    /**
     * Cancel a booking (Patient self-cancel or Admin/Owner cancel)
     */
    public function cancel(Request $request, Booking $booking)
    {
        // Authorization check
        if (
            auth()->user()->hasRole("patient") &&
            $booking->user_id !== auth()->id()
        ) {
            abort(403, "Unauthorized");
        }

        // Check if booking can be cancelled
        if ($booking->status === "selesai") {
            return back()->with(
                "error",
                "Booking yang sudah selesai tidak dapat dibatalkan!",
            );
        }

        if ($booking->status === "batal") {
            return back()->with("error", "Booking ini sudah dibatalkan!");
        }

        // Patient-specific cancellation rules
        if (auth()->user()->hasRole("patient")) {
            if (!$booking->can_cancel) {
                return back()->with(
                    "error",
                    "Booking tidak dapat dibatalkan (minimal 2 jam sebelum jadwal)!",
                );
            }
        }

        $validated = $request->validate([
            "cancellation_reason" => "nullable|string|max:500",
        ]);

        $booking->update([
            "status" => "batal",
            "cancelled_at" => now(),
            "cancellation_reason" =>
                $validated["cancellation_reason"] ??
                "Dibatalkan oleh " .
                    (auth()->user()->hasRole("patient") ? "pasien" : "admin"),
        ]);

        $message = "Booking berhasil dibatalkan!";

        if (auth()->user()->hasRole("patient")) {
            return redirect()->route("booking.mine")->with("success", $message);
        }

        return redirect()->route("booking.index")->with("success", $message);
    }

    /**
     * Check-in a booking (Admin/Owner - change status from booking to menunggu)
     */
    public function checkIn(Booking $booking)
    {
        if ($booking->status !== "booking") {
            return back()->with(
                "error",
                "Booking ini tidak dalam status booking!",
            );
        }

        $booking->update([
            "status" => "menunggu",
            "check_in_time" => now(),
        ]);

        return back()->with(
            "success",
            "Pasien berhasil check-in! Nomor antrian: " .
                $booking->formatted_queue_number,
        );
    }

    /**
     * Start service (Admin/Owner - change status from menunggu to berlangsung)
     */
    public function startService(Booking $booking)
    {
        if ($booking->status !== "menunggu") {
            return back()->with(
                "error",
                "Booking ini tidak dalam status menunggu!",
            );
        }

        $booking->update([
            "status" => "berlangsung",
            "service_start_time" => now(),
        ]);

        return back()->with(
            "success",
            "Pelayanan dimulai untuk pasien: " . $booking->user->name,
        );
    }

    /**
     * Finish service (Admin/Owner - change status from berlangsung to selesai)
     */
    public function finishService(Booking $booking)
    {
        if ($booking->status !== "berlangsung") {
            return back()->with(
                "error",
                "Booking ini tidak dalam status berlangsung!",
            );
        }

        $booking->update([
            "status" => "selesai",
            "service_end_time" => now(),
        ]);

        return back()->with(
            "success",
            "Pelayanan selesai untuk pasien: " . $booking->user->name,
        );
    }

    /**
     * Get available dates for booking (next 7 days)
     */
    private function getAvailableDates()
    {
        $dates = [];

        for ($i = 0; $i < 7; $i++) {
            $date = now()->addDays($i);
            $dateString = $date->format("Y-m-d");

            $canBook = Booking::canBookDate($dateString);

            $dates[] = [
                "date" => $dateString,
                "formatted_date" => $date
                    ->locale("id")
                    ->translatedFormat("l, d M Y"),
                "can_book" => $canBook["can_book"],
                "reason" => $canBook["reason"] ?? null,
                "available_slots" => $canBook["available_slots"] ?? 0,
            ];
        }

        return $dates;
    }

    /**
     * Get available slots for a specific date (AJAX)
     */
    public function checkSlots(Request $request)
    {
        $date = $request->input("date");

        if (!$date) {
            return response()->json(["error" => "Date is required"], 400);
        }

        $canBook = Booking::canBookDate($date, auth()->id());

        return response()->json([
            "can_book" => $canBook["can_book"],
            "reason" => $canBook["reason"] ?? null,
            "available_slots" => $canBook["available_slots"] ?? 0,
            "schedule" => $canBook["schedule"] ?? null,
        ]);
    }
}
