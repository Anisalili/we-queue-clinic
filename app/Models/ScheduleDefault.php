<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleDefault extends Model
{
    use HasFactory;

    protected $fillable = [
        "day_of_week",
        "start_time",
        "end_time",
        "max_slots",
        "is_active",
    ];

    protected $casts = [
        "is_active" => "boolean",
        "start_time" => "datetime:H:i",
        "end_time" => "datetime:H:i",
    ];

    /**
     * Get day name in Indonesian
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            "monday" => "Senin",
            "tuesday" => "Selasa",
            "wednesday" => "Rabu",
            "thursday" => "Kamis",
            "friday" => "Jumat",
            "saturday" => "Sabtu",
            "sunday" => "Minggu",
        ];

        return $days[$this->day_of_week] ?? $this->day_of_week;
    }

    /**
     * Scope for active schedules
     */
    public function scopeActive($query)
    {
        return $query->where("is_active", true);
    }

    /**
     * Order by day of week (SQLite compatible)
     */
    public function scopeOrderedByDay($query)
    {
        // SQLite-compatible ordering using CASE
        return $query->orderByRaw(
            "CASE day_of_week
                WHEN 'monday' THEN 1
                WHEN 'tuesday' THEN 2
                WHEN 'wednesday' THEN 3
                WHEN 'thursday' THEN 4
                WHEN 'friday' THEN 5
                WHEN 'saturday' THEN 6
                WHEN 'sunday' THEN 7
                ELSE 8
            END",
        );
    }
}
