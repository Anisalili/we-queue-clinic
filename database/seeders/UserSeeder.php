<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Dokter
        User::create([
            "name" => "Dr. Sehat",
            "email" => "dokter@clinic.test",
            "password" => Hash::make("password"),
            "phone" => "081234567890",
            "address" => "Jl. Klinik Sehat No. 123",
            "date_of_birth" => "1980-01-01",
        ])->assignRole("dokter");

        // Admin
        User::create([
            "name" => "Admin Klinik",
            "email" => "admin@clinic.test",
            "password" => Hash::make("password"),
            "phone" => "081234567891",
            "address" => "Jl. Admin No. 456",
            "date_of_birth" => "1990-05-15",
        ])->assignRole("admin");

        // Primary patient (for dashboard preview)
        User::create([
            "name" => "Pasien Test",
            "email" => "patient@clinic.test",
            "password" => Hash::make("password"),
            "phone" => "081234567892",
            "address" => "Jl. Pasien No. 789",
            "date_of_birth" => "1995-10-20",
        ])->assignRole("patient");

        // Extra patients used by BookingSeeder for queue preview
        $extras = [
            ["name" => "Budi Antrian", "email" => "budi.queue@clinic.test"],
            ["name" => "Sinta Antrian", "email" => "sinta.queue@clinic.test"],
            ["name" => "Andi Antrian", "email" => "andi.queue@clinic.test"],
        ];
        foreach ($extras as $data) {
            User::create([
                "name" => $data["name"],
                "email" => $data["email"],
                "password" => Hash::make("password"),
            ])->assignRole("patient");
        }
    }
}
