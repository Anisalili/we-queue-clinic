<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ScheduleOverride extends Model
{
    use HasFactory;

    protected $fillable = [
        "date",
        "start_time",
        "end_time",
        "max_slots",
        "is_closed",
        "reason",
    ];

    protected $casts = [
        "date" => "date",
        "start_time" => "datetime:H:i",
        "end_time" => "datetime:H:i",
        "is_closed" => "boolean",
    ];

    /**
     * Get formatted date
     */
    public function getFormattedDateAttribute(): string
    {
        return Carbon::parse($this->date)->format("d F Y");
    }

    /**
     * Scope for upcoming overrides
     */
    public function scopeUpcoming($query)
    {
        return $query
            ->where("date", ">=", now()->toDateString())
            ->orderBy("date", "asc");
    }

    /**
     * Scope for past overrides
     */
    public function scopePast($query)
    {
        return $query
            ->where("date", "<", now()->toDateString())
            ->orderBy("date", "desc");
    }

    /**
     * Scope for closed days
     */
    public function scopeClosed($query)
    {
        return $query->where("is_closed", true);
    }
}
