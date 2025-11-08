<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Display main report page
     */
    public function index(Request $request)
    {
        // Default date range: last 30 days
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->input('category', null);
        $status = $request->input('status', null);
        $activeTab = $request->input('tab', 'summary');

        // Get data based on active tab
        $data = [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'category' => $category,
            'status' => $status,
            'active_tab' => $activeTab,
        ];

        if ($activeTab === 'summary') {
            $data['summary'] = $this->reportService->getSummaryData($startDate, $endDate, $category, $status);
            $data['category_chart'] = $this->reportService->getCategoryChartData($startDate, $endDate);
            $data['daily_trend_chart'] = $this->reportService->getDailyTrendChartData($startDate, $endDate);
        } elseif ($activeTab === 'detailed') {
            $data['bookings'] = $this->reportService->getDetailedData($startDate, $endDate, $category, $status);
        } elseif ($activeTab === 'performance') {
            $data['performance'] = $this->reportService->getPerformanceData($startDate, $endDate);
            $data['kpi'] = $this->reportService->getKPIMetrics($startDate, $endDate);
        }

        return view('report.index', $data);
    }

    /**
     * Get summary data (AJAX)
     */
    public function summary(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->input('category', null);
        $status = $request->input('status', null);

        $summary = $this->reportService->getSummaryData($startDate, $endDate, $category, $status);
        $categoryChart = $this->reportService->getCategoryChartData($startDate, $endDate);
        $dailyTrendChart = $this->reportService->getDailyTrendChartData($startDate, $endDate);

        return response()->json([
            'summary' => $summary,
            'category_chart' => $categoryChart,
            'daily_trend_chart' => $dailyTrendChart,
        ]);
    }

    /**
     * Get detailed data (AJAX)
     */
    public function detailed(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->input('category', null);
        $status = $request->input('status', null);

        $bookings = $this->reportService->getDetailedData($startDate, $endDate, $category, $status);

        return response()->json([
            'bookings' => $bookings,
        ]);
    }

    /**
     * Get performance data (AJAX)
     */
    public function performance(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $performance = $this->reportService->getPerformanceData($startDate, $endDate);
        $kpi = $this->reportService->getKPIMetrics($startDate, $endDate);

        return response()->json([
            'performance' => $performance,
            'kpi' => $kpi,
        ]);
    }

    /**
     * Export to Excel (placeholder - requires maatwebsite/excel package)
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->input('category', null);
        $status = $request->input('status', null);

        // TODO: Implement Excel export using maatwebsite/excel
        // For now, return CSV
        $bookings = $this->reportService->getDetailedData($startDate, $endDate, $category, $status);

        $filename = 'report-' . $startDate . '-to-' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($bookings) {
            $file = fopen('php://output', 'w');

            // Header
            fputcsv($file, [
                'Tanggal',
                'No. Antrian',
                'Nama Pasien',
                'Kategori',
                'Status',
                'Tipe Booking',
                'Waktu Check-in',
                'Waktu Mulai',
                'Waktu Selesai',
                'Durasi (menit)',
            ]);

            // Data
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->booking_date->format('Y-m-d'),
                    $booking->formatted_queue_number,
                    $booking->user->name,
                    strtoupper($booking->patient_category),
                    ucfirst($booking->status),
                    ucfirst($booking->booking_type),
                    $booking->check_in_time ? $booking->check_in_time->format('Y-m-d H:i:s') : '-',
                    $booking->service_start_time ? $booking->service_start_time->format('Y-m-d H:i:s') : '-',
                    $booking->service_end_time ? $booking->service_end_time->format('Y-m-d H:i:s') : '-',
                    $booking->service_duration ?? '-',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to PDF (placeholder - requires barryvdh/laravel-dompdf package)
     */
    public function exportPdf(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->input('category', null);
        $status = $request->input('status', null);

        $summary = $this->reportService->getSummaryData($startDate, $endDate, $category, $status);

        // TODO: Implement PDF export using barryvdh/laravel-dompdf
        // For now, return a simple HTML view
        return view('report.pdf', [
            'start_date' => $startDate,
            'end_date' => $endDate,
            'category' => $category,
            'status' => $status,
            'summary' => $summary,
        ]);
    }
}
