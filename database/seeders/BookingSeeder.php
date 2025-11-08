<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get patient user
        $patient = User::where("email", "patient@clinic.test")->first();

        if (!$patient) {
            $this->command->warn(
                "Patient user not found. Skipping booking seeder.",
            );
            return;
        }

        // Create bookings for today
        $today = now();

        // Booking 1: Online booking - status menunggu (already checked in)
        Booking::create([
            "user_id" => $patient->id,
            "booking_date" => $today,
            "queue_number" => 1,
            "patient_category" => "bpjs",
            "status" => "menunggu",
            "booking_type" => "online",
            "check_in_time" => $today->copy()->setTime(7, 30),
            "created_at" => $today->copy()->subDay()->setTime(14, 0),
        ]);

        // Booking 2: Online booking - status booking (not yet checked in)
        Booking::create([
            "user_id" => $patient->id,
            "booking_date" => $today->copy()->addDay(),
            "queue_number" => 1,
            "patient_category" => "umum",
            "status" => "booking",
            "booking_type" => "online",
            "created_at" => $today->copy()->setTime(10, 0),
        ]);

        // Booking 3: Past booking - selesai
        Booking::create([
            "user_id" => $patient->id,
            "booking_date" => $today->copy()->subDays(3),
            "queue_number" => 5,
            "patient_category" => "bpjs",
            "status" => "selesai",
            "booking_type" => "online",
            "check_in_time" => $today->copy()->subDays(3)->setTime(8, 0),
            "service_start_time" => $today->copy()->subDays(3)->setTime(9, 15),
            "service_end_time" => $today->copy()->subDays(3)->setTime(9, 30),
            "created_at" => $today->copy()->subDays(4),
        ]);

        // Booking 4: Past booking - batal
        Booking::create([
            "user_id" => $patient->id,
            "booking_date" => $today->copy()->subDays(7),
            "queue_number" => 3,
            "patient_category" => "umum",
            "status" => "batal",
            "booking_type" => "online",
            "cancelled_at" => $today->copy()->subDays(7)->setTime(6, 0),
            "cancellation_reason" => "Dibatalkan oleh pasien",
            "created_at" => $today->copy()->subDays(8),
        ]);

        // Booking 5: Future booking
        Booking::create([
            "user_id" => $patient->id,
            "booking_date" => $today->copy()->addDays(3),
            "queue_number" => 1,
            "patient_category" => "bpjs",
            "status" => "booking",
            "booking_type" => "online",
            "created_at" => $today->copy()->setTime(11, 0),
        ]);

        $this->command->info("✅ Created 5 test bookings for patient");
    }
}
