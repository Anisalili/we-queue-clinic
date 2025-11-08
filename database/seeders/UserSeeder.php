<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Owner account
        $owner = User::create([
            "name" => "Dr. Owner",
            "email" => "owner@clinic.test",
            "password" => Hash::make("password"),
            "phone" => "081234567890",
            "address" => "Jl. Klinik Sehat No. 123",
            "date_of_birth" => "1980-01-01",
        ]);
        $owner->assignRole("owner");

        // Create Admin account
        $admin = User::create([
            "name" => "Admin Klinik",
            "email" => "admin@clinic.test",
            "password" => Hash::make("password"),
            "phone" => "081234567891",
            "address" => "Jl. Admin No. 456",
            "date_of_birth" => "1990-05-15",
        ]);
        $admin->assignRole("admin");

        // Create Patient account
        $patient = User::create([
            "name" => "Pasien Test",
            "email" => "patient@clinic.test",
            "password" => Hash::make("password"),
            "phone" => "081234567892",
            "address" => "Jl. Pasien No. 789",
            "date_of_birth" => "1995-10-20",
        ]);
        $patient->assignRole("patient");
    }
}
