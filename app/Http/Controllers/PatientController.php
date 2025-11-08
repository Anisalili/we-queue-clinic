<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class PatientController extends Controller
{
    /**
     * Show walk-in registration form
     */
    public function register()
    {
        return view("patient.register");
    }

    /**
     * Store walk-in patient and create booking
     */
    public function storeWalkin(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            "user_id" => "nullable|exists:users,id",
            "is_new_patient" => "required|boolean",
            "name" => "required_if:is_new_patient,1|string|max:255",
            "email" =>
                "required_if:is_new_patient,1|email|unique:users,email," .
                $request->user_id,
            "phone" => "required|string|max:20",
            "date_of_birth" => "nullable|date",
            "address" => "nullable|string",
            "patient_category" => "required|in:bpjs,umum",
            "notes" => "nullable|string|max:500",
        ]);

        // Check slot availability for today
        $canBook = Booking::canBookDate(today());

        if (!$canBook["can_book"]) {
            return back()->with("error", $canBook["reason"])->withInput();
        }

        // Get or create patient user
        if ($validated["is_new_patient"]) {
            // Create new patient
            $user = User::create([
                "name" => $validated["name"],
                "email" => $validated["email"],
                "phone" => $validated["phone"],
                "date_of_birth" => $validated["date_of_birth"] ?? null,
                "address" => $validated["address"] ?? null,
                "password" => Hash::make(Str::random(16)), // Random password
            ]);

            // Assign patient role
            $user->assignRole("patient");
        } else {
            // Use existing patient
            $user = User::findOrFail($validated["user_id"]);

            // Update phone if provided
            if ($validated["phone"]) {
                $user->update(["phone" => $validated["phone"]]);
            }
        }

        // Check if user already has active booking
        $activeBooking = Booking::where("user_id", $user->id)
            ->whereIn("status", ["booking", "menunggu", "berlangsung"])
            ->first();

        if ($activeBooking) {
            return back()
                ->with(
                    "error",
                    "Pasien ini masih memiliki booking aktif! Selesaikan booking tersebut terlebih dahulu.",
                )
                ->withInput();
        }

        // Get next queue number
        $queueNumber = Booking::getNextQueueNumber(today());

        // Create booking with walk-in type and status menunggu (already present)
        $booking = Booking::create([
            "user_id" => $user->id,
            "booking_date" => today(),
            "queue_number" => $queueNumber,
            "patient_category" => $validated["patient_category"],
            "status" => "menunggu", // Walk-in patients immediately go to waiting status
            "booking_type" => "walk-in",
            "check_in_time" => now(), // Auto check-in
            "notes" => $validated["notes"] ?? null,
        ]);

        return redirect()
            ->route("booking.show", $booking)
            ->with(
                "success",
                "Pasien walk-in berhasil didaftarkan! Nomor Antrian: " .
                    $booking->formatted_queue_number,
            );
    }

    /**
     * Search patients (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $query = $request->input("q");

        if (strlen($query) < 3) {
            return response()->json([]);
        }

        $patients = User::role("patient")
            ->where(function ($q) use ($query) {
                $q->where("name", "like", "%" . $query . "%")
                    ->orWhere("email", "like", "%" . $query . "%")
                    ->orWhere("phone", "like", "%" . $query . "%");
            })
            ->limit(10)
            ->get(["id", "name", "email", "phone", "date_of_birth", "address"]);

        return response()->json($patients);
    }
}
