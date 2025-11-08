<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Holiday extends Model
{
    use HasFactory;

    protected $fillable = ["date", "name", "type", "description"];

    protected $casts = [
        "date" => "date",
    ];

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->format("d F Y");
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        $labels = [
            "national" => "Libur Nasional",
            "clinic_leave" => "Cuti Klinik",
            "emergency" => "Darurat",
        ];

        return $labels[$this->type] ?? $this->type;
    }

    /**
     * Scope for upcoming holidays
     */
    public function scopeUpcoming($query)
    {
        return $query
            ->where("date", ">=", now()->toDateString())
            ->orderBy("date", "asc");
    }

    /**
     * Scope for this year
     */
    public function scopeThisYear($query)
    {
        return $query->whereYear("date", now()->year);
    }

    /**
     * Check if a date is a holiday
     */
    public static function isHoliday($date): bool
    {
        return self::where(
            "date",
            Carbon::parse($date)->toDateString(),
        )->exists();
    }
}
