<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $tz = config("app.timezone", "UTC");
        $patient = User::where("email", "patient@clinic.test")->first();
        $budi = User::where("email", "budi.queue@clinic.test")->first();
        $sinta = User::where("email", "sinta.queue@clinic.test")->first();
        $andi = User::where("email", "andi.queue@clinic.test")->first();

        if (!$patient) {
            $this->command->warn("Primary patient missing. Run UserSeeder first.");
            return;
        }

        $today = Carbon::today($tz);

        // ---- Past & future bookings for primary patient ----
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

        // ---- Tomorrow: queue preview (primary patient has active booking) ----
        $tomorrow = $today->copy()->addDay();
        $start = $tomorrow->copy()->setTime(8, 0);

        if ($budi) {
            Booking::create([
                "user_id" => $budi->id,
                "booking_date" => $tomorrow,
                "queue_number" => 1,
                "patient_category" => "bpjs",
                "status" => "berlangsung",
                "booking_type" => "online",
                "check_in_time" => $start->copy()->subMinutes(15),
                "service_start_time" => $start,
                "created_at" => $start->copy()->subDay(),
            ]);
        }

        if ($sinta) {
            Booking::create([
                "user_id" => $sinta->id,
                "booking_date" => $tomorrow,
                "queue_number" => 2,
                "patient_category" => "umum",
                "status" => "menunggu",
                "booking_type" => "online",
                "check_in_time" => $start->copy()->addMinutes(10),
                "created_at" => $start->copy()->subDay()->addHour(),
            ]);
        }

        Booking::create([
            "user_id" => $patient->id,
            "booking_date" => $tomorrow,
            "queue_number" => 3,
            "patient_category" => "bpjs",
            "status" => "menunggu",
            "booking_type" => "online",
            "check_in_time" => $start->copy()->addMinutes(20),
            "created_at" => $start->copy()->subDay()->addHours(2),
        ]);

        if ($andi) {
            Booking::create([
                "user_id" => $andi->id,
                "booking_date" => $tomorrow,
                "queue_number" => 4,
                "patient_category" => "umum",
                "status" => "menunggu",
                "booking_type" => "online",
                "check_in_time" => $start->copy()->addMinutes(30),
                "created_at" => $start->copy()->subDay()->addHours(3),
            ]);
        }
    }
}
