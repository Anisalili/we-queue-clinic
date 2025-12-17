<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientQueuePreviewSeeder extends Seeder
{
    /**
     * Seed queue data for patient dashboard preview (tomorrow's date).
     */
    public function run(): void
    {
        $timezone = config("app.timezone", "UTC");
        $targetDate = Carbon::now($timezone)
            ->addDay()
            ->toDateString();

        // Prepare patients (reuse if already exist)
        $primaryPatient =
            User::where("email", "patient@clinic.test")->first() ??
            User::create([
                "name" => "Pasien Preview",
                "email" => "patient@clinic.test",
                "password" => Hash::make("password"),
            ]);
        $primaryPatient->assignRole("patient");

        $otherPatients = collect([
            ["name" => "Budi Antrian", "email" => "budi.queue@clinic.test"],
            ["name" => "Sinta Antrian", "email" => "sinta.queue@clinic.test"],
            ["name" => "Andi Antrian", "email" => "andi.queue@clinic.test"],
        ])->map(function ($data) {
            $user =
                User::where("email", $data["email"])->first() ??
                User::create([
                    "name" => $data["name"],
                    "email" => $data["email"],
                    "password" => Hash::make("password"),
                ]);
            $user->assignRole("patient");
            return $user;
        });

        // Clean existing bookings for the target date to avoid duplicates
        Booking::whereDate("booking_date", $targetDate)->delete();

        $startTime = Carbon::parse($targetDate, $timezone)->setTime(8, 0);

        // Currently being served
        Booking::create([
            "user_id" => $otherPatients[0]->id,
            "booking_date" => $targetDate,
            "queue_number" => 1,
            "patient_category" => "bpjs",
            "status" => "berlangsung",
            "booking_type" => "online",
            "check_in_time" => $startTime->copy()->subMinutes(15),
            "service_start_time" => $startTime,
            "created_at" => $startTime->copy()->subDay(),
        ]);

        // Waiting queue
        Booking::create([
            "user_id" => $otherPatients[1]->id,
            "booking_date" => $targetDate,
            "queue_number" => 2,
            "patient_category" => "umum",
            "status" => "menunggu",
            "booking_type" => "online",
            "check_in_time" => $startTime->copy()->addMinutes(10),
            "created_at" => $startTime->copy()->subDay()->addHour(),
        ]);

        // Patient's own booking (to preview dashboard)
        Booking::create([
            "user_id" => $primaryPatient->id,
            "booking_date" => $targetDate,
            "queue_number" => 3,
            "patient_category" => "bpjs",
            "status" => "menunggu",
            "booking_type" => "online",
            "check_in_time" => $startTime->copy()->addMinutes(20),
            "created_at" => $startTime->copy()->subDay()->addHours(2),
        ]);

        // Additional waiting
        Booking::create([
            "user_id" => $otherPatients[2]->id,
            "booking_date" => $targetDate,
            "queue_number" => 4,
            "patient_category" => "umum",
            "status" => "menunggu",
            "booking_type" => "online",
            "check_in_time" => $startTime->copy()->addMinutes(30),
            "created_at" => $startTime->copy()->subDay()->addHours(3),
        ]);

        $this->command->info(
            "✅ Seeded patient queue preview for {$targetDate}",
        );
    }
}
