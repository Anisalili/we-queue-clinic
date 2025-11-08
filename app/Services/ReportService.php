<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get summary report data
     */
    public function getSummaryData($startDate, $endDate, $category = null, $status = null)
    {
        $query = Booking::dateRange($startDate, $endDate);

        if ($category) {
            $query->where('patient_category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $bookings = $query->get();

        // Total patients
        $totalPatients = $bookings->count();

        // Breakdown by category
        $bpjsCount = $bookings->where('patient_category', 'bpjs')->count();
        $umumCount = $bookings->where('patient_category', 'umum')->count();

        $bpjsPercentage = $totalPatients > 0 ? round(($bpjsCount / $totalPatients) * 100, 2) : 0;
        $umumPercentage = $totalPatients > 0 ? round(($umumCount / $totalPatients) * 100, 2) : 0;

        // Average per day
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $totalDays = $start->diffInDays($end) + 1;
        $avgPerDay = $totalDays > 0 ? round($totalPatients / $totalDays, 2) : 0;

        // Breakdown by status
        $statusBreakdown = [
            'booking' => $bookings->where('status', 'booking')->count(),
            'menunggu' => $bookings->where('status', 'menunggu')->count(),
            'berlangsung' => $bookings->where('status', 'berlangsung')->count(),
            'selesai' => $bookings->where('status', 'selesai')->count(),
            'batal' => $bookings->where('status', 'batal')->count(),
        ];

        // Cancellation rate
        $totalCompleted = $statusBreakdown['selesai'];
        $totalCancelled = $statusBreakdown['batal'];
        $cancellationRate = $totalPatients > 0 ? round(($totalCancelled / $totalPatients) * 100, 2) : 0;

        // No-show rate (booking yang di-cancel)
        $noShowCount = Booking::dateRange($startDate, $endDate)
            ->where('status', 'batal')
            ->where('booking_type', 'online')
            ->whereNotNull('cancelled_at')
            ->count();

        $onlineBookings = Booking::dateRange($startDate, $endDate)
            ->where('booking_type', 'online')
            ->count();

        $noShowRate = $onlineBookings > 0 ? round(($noShowCount / $onlineBookings) * 100, 2) : 0;

        return [
            'total_patients' => $totalPatients,
            'bpjs_count' => $bpjsCount,
            'umum_count' => $umumCount,
            'bpjs_percentage' => $bpjsPercentage,
            'umum_percentage' => $umumPercentage,
            'avg_per_day' => $avgPerDay,
            'status_breakdown' => $statusBreakdown,
            'cancellation_rate' => $cancellationRate,
            'no_show_rate' => $noShowRate,
            'total_completed' => $totalCompleted,
            'total_cancelled' => $totalCancelled,
        ];
    }

    /**
     * Get detailed report data
     */
    public function getDetailedData($startDate, $endDate, $category = null, $status = null)
    {
        $query = Booking::with('user')
            ->dateRange($startDate, $endDate)
            ->orderBy('booking_date', 'desc')
            ->orderBy('queue_number', 'asc');

        if ($category) {
            $query->where('patient_category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        return $query->paginate(50);
    }

    /**
     * Get performance report data
     */
    public function getPerformanceData($startDate, $endDate)
    {
        // Average service time (overall)
        $avgServiceTime = Booking::getAverageServiceTime($startDate, $endDate);

        // Average service time by category
        $avgServiceTimeBpjs = Booking::getAverageServiceTime($startDate, $endDate, 'bpjs');
        $avgServiceTimeUmum = Booking::getAverageServiceTime($startDate, $endDate, 'umum');

        // Peak hours
        $peakHours = Booking::dateRange($startDate, $endDate)
            ->whereNotNull('service_start_time')
            ->get()
            ->groupBy(function($booking) {
                return $booking->service_start_time ? $booking->service_start_time->format('H') : null;
            })
            ->map(function($group) {
                return $group->count();
            })
            ->sortDesc()
            ->take(5);

        // Daily trend
        $dailyTrend = Booking::dateRange($startDate, $endDate)
            ->select(DB::raw('DATE(booking_date) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Booking type breakdown
        $bookingTypeBreakdown = Booking::dateRange($startDate, $endDate)
            ->select('booking_type', DB::raw('COUNT(*) as total'))
            ->groupBy('booking_type')
            ->get();

        return [
            'avg_service_time' => $avgServiceTime,
            'avg_service_time_bpjs' => $avgServiceTimeBpjs,
            'avg_service_time_umum' => $avgServiceTimeUmum,
            'peak_hours' => $peakHours,
            'daily_trend' => $dailyTrend,
            'booking_type_breakdown' => $bookingTypeBreakdown,
        ];
    }

    /**
     * Get KPI metrics
     */
    public function getKPIMetrics($startDate, $endDate)
    {
        $bookingSuccessRate = Booking::getBookingSuccessRate($startDate, $endDate);
        $cancellationRate = Booking::getCancellationRate($startDate, $endDate);
        $noShowRate = Booking::getNoShowRate($startDate, $endDate);
        $slotUtilization = Booking::getSlotUtilization($startDate, $endDate);

        return [
            'booking_success_rate' => $bookingSuccessRate,
            'cancellation_rate' => $cancellationRate,
            'no_show_rate' => $noShowRate,
            'slot_utilization' => $slotUtilization,
        ];
    }

    /**
     * Get category breakdown for chart
     */
    public function getCategoryChartData($startDate, $endDate)
    {
        $bpjsCount = Booking::dateRange($startDate, $endDate)
            ->where('patient_category', 'bpjs')
            ->count();

        $umumCount = Booking::dateRange($startDate, $endDate)
            ->where('patient_category', 'umum')
            ->count();

        return [
            'labels' => ['BPJS', 'Umum'],
            'data' => [$bpjsCount, $umumCount],
            'colors' => ['#198754', '#0d6efd'],
        ];
    }

    /**
     * Get daily trend for chart
     */
    public function getDailyTrendChartData($startDate, $endDate)
    {
        $dailyData = Booking::dateRange($startDate, $endDate)
            ->select(
                DB::raw('DATE(booking_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN patient_category = "bpjs" THEN 1 ELSE 0 END) as bpjs_count'),
                DB::raw('SUM(CASE WHEN patient_category = "umum" THEN 1 ELSE 0 END) as umum_count')
            )
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        return [
            'labels' => $dailyData->pluck('date')->map(function($date) {
                return Carbon::parse($date)->format('d M');
            })->toArray(),
            'datasets' => [
                [
                    'label' => 'BPJS',
                    'data' => $dailyData->pluck('bpjs_count')->toArray(),
                    'backgroundColor' => '#198754',
                ],
                [
                    'label' => 'Umum',
                    'data' => $dailyData->pluck('umum_count')->toArray(),
                    'backgroundColor' => '#0d6efd',
                ],
                [
                    'label' => 'Total',
                    'data' => $dailyData->pluck('total')->toArray(),
                    'backgroundColor' => '#6c757d',
                    'type' => 'line',
                ],
            ],
        ];
    }
}
