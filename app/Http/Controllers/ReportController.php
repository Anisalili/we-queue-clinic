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
     * Export detailed report to a real Excel (.xlsx) file using PhpSpreadsheet
     */
    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->input('category', null);
        $status = $request->input('status', null);

        $bookings = $this->reportService->getDetailedData($startDate, $endDate, $category, $status);

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan Booking');

        // Title row
        $sheet->mergeCells('A1:J1');
        $sheet->setCellValue('A1', 'LAPORAN KLINIK QLINIC - APOTEK ANNA FARMA');
        $sheet->mergeCells('A2:J2');
        $sheet->setCellValue('A2', 'Periode: ' . Carbon::parse($startDate)->translatedFormat('d M Y') . ' s/d ' . Carbon::parse($endDate)->translatedFormat('d M Y'));

        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        $sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // Column headers (row 4)
        $headerRow = 4;
        $headers = [
            'No',
            'Tanggal',
            'No. Antrian',
            'Nama Pasien',
            'Kategori',
            'Status',
            'Tipe Booking',
            'Waktu Check-in',
            'Waktu Mulai',
            'Waktu Selesai',
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . $headerRow, $header);
            $col++;
        }

        // Style header row
        $sheet->getStyle("A{$headerRow}:J{$headerRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4154F1'],
            ],
            'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER],
        ]);

        // Data rows
        $row = $headerRow + 1;
        $no = 1;
        foreach ($bookings as $booking) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $booking->booking_date->format('d/m/Y'));
            $sheet->setCellValueExplicit('C' . $row, $booking->formatted_queue_number, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValue('D' . $row, $booking->user->name);
            $sheet->setCellValue('E' . $row, strtoupper($booking->patient_category));
            $sheet->setCellValue('F' . $row, ucfirst($booking->status));
            $sheet->setCellValue('G' . $row, ucfirst($booking->booking_type));
            $sheet->setCellValue('H' . $row, $booking->check_in_time ? $booking->check_in_time->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('I' . $row, $booking->service_start_time ? $booking->service_start_time->format('d/m/Y H:i') : '-');
            $sheet->setCellValue('J' . $row, $booking->service_end_time ? $booking->service_end_time->format('d/m/Y H:i') : '-');
            $row++;
        }

        // Borders for the whole table
        $lastRow = max($row - 1, $headerRow);
        $sheet->getStyle("A{$headerRow}:J{$lastRow}")->getBorders()->getAllBorders()
            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Auto-size columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $filename = 'laporan-klinik-' . $startDate . '-sd-' . $endDate . '.xlsx';

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
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
