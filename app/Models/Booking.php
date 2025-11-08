<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\ScheduleDefault;
use App\Models\ScheduleOverride;
use App\Models\Holiday;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "booking_date",
        "queue_number",
        "patient_category",
        "status",
        "booking_type",
        "check_in_time",
        "service_start_time",
        "service_end_time",
        "cancelled_at",
        "cancellation_reason",
        "notes",
    ];

    protected $casts = [
        "booking_date" => "date",
        "check_in_time" => "datetime",
        "service_start_time" => "datetime",
        "service_end_time" => "datetime",
        "cancelled_at" => "datetime",
    ];

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereIn("status", [
            "booking",
            "menunggu",
            "berlangsung",
        ]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate("booking_date", today());
    }

    public function scopeUpcoming($query)
    {
        return $query
            ->where("booking_date", ">=", today())
            ->whereIn("status", ["booking", "menunggu"]);
    }

    public function scopeByDate($query, $date)
    {
        return $query->whereDate("booking_date", $date);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where("status", $status);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where("patient_category", $category);
    }

    public function scopeBpjs($query)
    {
        return $query->where("patient_category", "bpjs");
    }

    public function scopeUmum($query)
    {
        return $query->where("patient_category", "umum");
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween("booking_date", [$startDate, $endDate]);
    }

    public function scopeCompleted($query)
    {
        return $query->where("status", "selesai");
    }

    public function scopeCancelled($query)
    {
        return $query->where("status", "batal");
    }

    /**
     * Accessors
     */
    public function getCategoryBadgeAttribute()
    {
        return $this->patient_category === "bpjs"
            ? '<span class="badge bg-success">BPJS</span>'
            : '<span class="badge bg-primary">Umum</span>';
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            "booking" => '<span class="badge bg-warning">Booking</span>',
            "menunggu" => '<span class="badge bg-info">Menunggu</span>',
            "berlangsung" =>
                '<span class="badge bg-primary">Berlangsung</span>',
            "selesai" => '<span class="badge bg-success">Selesai</span>',
            "batal" => '<span class="badge bg-danger">Batal</span>',
        ];

        return $badges[$this->status] ??
            '<span class="badge bg-secondary">Unknown</span>';
    }

    public function getFormattedQueueNumberAttribute()
    {
        return str_pad($this->queue_number, 3, "0", STR_PAD_LEFT);
    }

    public function getCanCancelAttribute()
    {
        // Patient can cancel if:
        // 1. Status is 'booking'
        // 2. Booking date is at least 2 hours from now
        if ($this->status !== "booking") {
            return false;
        }

        $bookingDateTime = Carbon::parse($this->booking_date);
        $minCancelTime = now()->addHours(2);

        return $bookingDateTime->isAfter($minCancelTime);
    }

    public function getServiceDurationAttribute()
    {
        if (!$this->service_start_time || !$this->service_end_time) {
            return null;
        }

        return $this->service_start_time->diffInMinutes(
            $this->service_end_time,
        );
    }

    /**
     * Static methods
     */
    public static function getNextQueueNumber($date)
    {
        $lastBooking = self::whereDate("booking_date", $date)
            ->orderBy("queue_number", "desc")
            ->first();

        return $lastBooking ? $lastBooking->queue_number + 1 : 1;
    }

    public static function getAvailableSlots($date)
    {
        // Get schedule for the date
        $schedule = self::getScheduleForDate($date);

        if (!$schedule || $schedule["is_closed"]) {
            return 0;
        }

        $maxSlots = $schedule["max_slots"];
        $usedSlots = self::whereDate("booking_date", $date)
            ->whereNotIn("status", ["batal"])
            ->count();

        return max(0, $maxSlots - $usedSlots);
    }

    public static function getScheduleForDate($date)
    {
        $carbonDate = Carbon::parse($date);

        // Check if there's a holiday
        $holiday = Holiday::whereDate("date", $date)->first();
        if ($holiday) {
            return [
                "is_closed" => true,
                "reason" => "Holiday: " . $holiday->name,
            ];
        }

        // Check for override
        $override = ScheduleOverride::whereDate("date", $date)->first();
        if ($override) {
            return [
                "is_closed" => $override->is_closed,
                "start_time" => $override->start_time,
                "end_time" => $override->end_time,
                "max_slots" => $override->max_slots ?? 0,
                "reason" => $override->reason,
            ];
        }

        // Get default schedule
        $dayOfWeek = strtolower($carbonDate->format("l")); // monday, tuesday, etc.
        $defaultSchedule = ScheduleDefault::where("day_of_week", $dayOfWeek)
            ->where("is_active", true)
            ->first();

        if (!$defaultSchedule) {
            return [
                "is_closed" => true,
                "reason" => "No schedule configured",
            ];
        }

        return [
            "is_closed" => false,
            "start_time" => $defaultSchedule->start_time,
            "end_time" => $defaultSchedule->end_time,
            "max_slots" => $defaultSchedule->max_slots,
        ];
    }

    public static function canBookDate($date, $userId = null)
    {
        $carbonDate = Carbon::parse($date);

        // Check if date is in the past
        if ($carbonDate->isPast() && !$carbonDate->isToday()) {
            return [
                "can_book" => false,
                "reason" => "Tanggal sudah lewat",
            ];
        }

        // Check if date is more than 7 days in the future
        if ($carbonDate->isAfter(now()->addDays(7))) {
            return [
                "can_book" => false,
                "reason" => "Booking maksimal 7 hari ke depan",
            ];
        }

        // Check schedule
        $schedule = self::getScheduleForDate($date);
        if ($schedule["is_closed"]) {
            return [
                "can_book" => false,
                "reason" => $schedule["reason"] ?? "Klinik tutup",
            ];
        }

        // Check available slots
        $availableSlots = self::getAvailableSlots($date);
        if ($availableSlots <= 0) {
            return [
                "can_book" => false,
                "reason" => "Slot penuh",
            ];
        }

        // Check if user already has active booking
        if ($userId) {
            $activeBooking = self::where("user_id", $userId)
                ->whereIn("status", ["booking", "menunggu"])
                ->first();

            if ($activeBooking) {
                return [
                    "can_book" => false,
                    "reason" => "Anda masih memiliki booking aktif",
                ];
            }
        }

        return [
            "can_book" => true,
            "available_slots" => $availableSlots,
            "schedule" => $schedule,
        ];
    }

    /**
     * Report helper methods
     */
    public static function getBookingSuccessRate($startDate, $endDate)
    {
        $total = self::dateRange($startDate, $endDate)->count();
        $success = self::dateRange($startDate, $endDate)
            ->where("status", "selesai")
            ->count();

        return $total > 0 ? round(($success / $total) * 100, 2) : 0;
    }

    public static function getCancellationRate($startDate, $endDate)
    {
        $total = self::dateRange($startDate, $endDate)->count();
        $cancelled = self::dateRange($startDate, $endDate)
            ->where("status", "batal")
            ->count();

        return $total > 0 ? round(($cancelled / $total) * 100, 2) : 0;
    }

    public static function getNoShowRate($startDate, $endDate)
    {
        $onlineBookings = self::dateRange($startDate, $endDate)
            ->where("booking_type", "online")
            ->count();

        $noShow = self::dateRange($startDate, $endDate)
            ->where("booking_type", "online")
            ->where("status", "batal")
            ->whereNotNull("cancelled_at")
            ->count();

        return $onlineBookings > 0
            ? round(($noShow / $onlineBookings) * 100, 2)
            : 0;
    }

    public static function getAverageServiceTime(
        $startDate,
        $endDate,
        $category = null,
    ) {
        $query = self::dateRange($startDate, $endDate)
            ->whereNotNull("service_start_time")
            ->whereNotNull("service_end_time");

        if ($category) {
            $query->where("patient_category", $category);
        }

        $bookings = $query->get();

        if ($bookings->isEmpty()) {
            return 0;
        }

        $totalMinutes = $bookings->sum(function ($booking) {
            return $booking->service_duration;
        });

        return round($totalMinutes / $bookings->count(), 2);
    }

    public static function getSlotUtilization($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);

        $totalSlots = 0;
        $usedSlots = 0;

        // Calculate total available slots for date range
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            $schedule = self::getScheduleForDate($date);

            if (!$schedule["is_closed"]) {
                $totalSlots += $schedule["max_slots"];

                $used = self::whereDate("booking_date", $date)
                    ->whereNotIn("status", ["batal"])
                    ->count();

                $usedSlots += $used;
            }
        }

        return $totalSlots > 0 ? round(($usedSlots / $totalSlots) * 100, 2) : 0;
    }
}
