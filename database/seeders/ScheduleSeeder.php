<?php

namespace Database\Seeders;

use App\Models\ScheduleDefault;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                "day_of_week" => "monday",
                "start_time" => "08:00",
                "end_time" => "15:00",
                "max_slots" => 30,
                "is_active" => true,
            ],
            [
                "day_of_week" => "tuesday",
                "start_time" => "08:00",
                "end_time" => "15:00",
                "max_slots" => 30,
                "is_active" => true,
            ],
            [
                "day_of_week" => "wednesday",
                "start_time" => "08:00",
                "end_time" => "15:00",
                "max_slots" => 30,
                "is_active" => true,
            ],
            [
                "day_of_week" => "thursday",
                "start_time" => "08:00",
                "end_time" => "15:00",
                "max_slots" => 30,
                "is_active" => true,
            ],
            [
                "day_of_week" => "friday",
                "start_time" => "08:00",
                "end_time" => "15:00",
                "max_slots" => 30,
                "is_active" => true,
            ],
            [
                "day_of_week" => "saturday",
                "start_time" => "08:00",
                "end_time" => "12:00",
                "max_slots" => 20,
                "is_active" => true,
            ],
            [
                "day_of_week" => "sunday",
                "start_time" => null,
                "end_time" => null,
                "max_slots" => 0,
                "is_active" => false,
            ],
        ];

        foreach ($schedules as $schedule) {
            ScheduleDefault::create($schedule);
        }
    }
}
