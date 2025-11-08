<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Klinik - {{ $start_date }} s/d {{ $end_date }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #435ebe;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #435ebe;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 14px;
        }
        .info-box {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-box table {
            width: 100%;
        }
        .info-box td {
            padding: 5px;
        }
        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
            background: #fff;
        }
        .stat-card h3 {
            font-size: 28px;
            color: #435ebe;
            margin-bottom: 5px;
        }
        .stat-card p {
            color: #666;
            font-size: 11px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 16px;
            color: #435ebe;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background: #435ebe;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
        }
        table.data-table td {
            padding: 8px;
            border-bottom: 1px solid #dee2e6;
            font-size: 11px;
        }
        table.data-table tr:nth-child(even) {
            background: #f8f9fa;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success { background: #198754; color: white; }
        .badge-primary { background: #0d6efd; color: white; }
        .badge-warning { background: #ffc107; color: black; }
        .badge-danger { background: #dc3545; color: white; }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #dee2e6;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #e9ecef;
            border-radius: 3px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #435ebe;
            text-align: center;
            color: white;
            font-size: 10px;
            line-height: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KLINIK</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</p>
        @if($category)
            <p>Kategori: <strong>{{ strtoupper($category) }}</strong></p>
        @endif
        @if($status)
            <p>Status: <strong>{{ ucfirst($status) }}</strong></p>
        @endif
    </div>

    <div class="info-box">
        <table>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ now()->format('d F Y H:i') }}</td>
                <td><strong>Dicetak oleh:</strong></td>
                <td>{{ auth()->user()->name }}</td>
            </tr>
        </table>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ $summary['total_patients'] ?? 0 }}</h3>
            <p>Total Pasien</p>
        </div>
        <div class="stat-card">
            <h3>{{ $summary['bpjs_count'] ?? 0 }}</h3>
            <p>Pasien BPJS<br>({{ $summary['bpjs_percentage'] ?? 0 }}%)</p>
        </div>
        <div class="stat-card">
            <h3>{{ $summary['umum_count'] ?? 0 }}</h3>
            <p>Pasien Umum<br>({{ $summary['umum_percentage'] ?? 0 }}%)</p>
        </div>
        <div class="stat-card">
            <h3>{{ $summary['avg_per_day'] ?? 0 }}</h3>
            <p>Rata-rata/Hari</p>
        </div>
    </div>

    <div class="section">
        <h2>Ringkasan Status</h2>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Status</th>
                    <th style="text-align: center;">Jumlah</th>
                    <th style="text-align: center;">Persentase</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total = $summary['total_patients'] ?? 0;
                @endphp
                <tr>
                    <td><span class="badge badge-warning">Booking</span></td>
                    <td style="text-align: center;">{{ $summary['status_breakdown']['booking'] ?? 0 }}</td>
                    <td style="text-align: center;">
                        {{ $total > 0 ? round((($summary['status_breakdown']['booking'] ?? 0) / $total) * 100, 2) : 0 }}%
                    </td>
                </tr>
                <tr>
                    <td><span class="badge badge-primary">Menunggu</span></td>
                    <td style="text-align: center;">{{ $summary['status_breakdown']['menunggu'] ?? 0 }}</td>
                    <td style="text-align: center;">
                        {{ $total > 0 ? round((($summary['status_breakdown']['menunggu'] ?? 0) / $total) * 100, 2) : 0 }}%
                    </td>
                </tr>
                <tr>
                    <td><span class="badge badge-primary">Berlangsung</span></td>
                    <td style="text-align: center;">{{ $summary['status_breakdown']['berlangsung'] ?? 0 }}</td>
                    <td style="text-align: center;">
                        {{ $total > 0 ? round((($summary['status_breakdown']['berlangsung'] ?? 0) / $total) * 100, 2) : 0 }}%
                    </td>
                </tr>
                <tr>
                    <td><span class="badge badge-success">Selesai</span></td>
                    <td style="text-align: center;"><strong>{{ $summary['status_breakdown']['selesai'] ?? 0 }}</strong></td>
                    <td style="text-align: center;">
                        <strong>{{ $total > 0 ? round((($summary['status_breakdown']['selesai'] ?? 0) / $total) * 100, 2) : 0 }}%</strong>
                    </td>
                </tr>
                <tr>
                    <td><span class="badge badge-danger">Batal</span></td>
                    <td style="text-align: center;">{{ $summary['status_breakdown']['batal'] ?? 0 }}</td>
                    <td style="text-align: center;">
                        {{ $total > 0 ? round((($summary['status_breakdown']['batal'] ?? 0) / $total) * 100, 2) : 0 }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Metrik Kinerja</h2>
        <table style="width: 100%; margin-top: 10px;">
            <tr>
                <td style="padding: 10px;">
                    <strong>Tingkat Pembatalan:</strong><br>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ min($summary['cancellation_rate'] ?? 0, 100) }}%; background: #dc3545;">
                            {{ $summary['cancellation_rate'] ?? 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;">
                    <strong>Tingkat No-Show:</strong><br>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ min($summary['no_show_rate'] ?? 0, 100) }}%; background: #ffc107; color: black;">
                            {{ $summary['no_show_rate'] ?? 0 }}%
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td style="padding: 10px;">
                    <strong>Completion Rate:</strong><br>
                    @php
                        $completionRate = $total > 0 ? round((($summary['total_completed'] ?? 0) / $total) * 100, 2) : 0;
                    @endphp
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $completionRate }}%; background: #198754;">
                            {{ $completionRate }}%
                        </div>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Laporan ini dicetak secara otomatis oleh sistem Web Queue Clinic</p>
        <p>{{ config('app.name') }} &copy; {{ date('Y') }}</p>
    </div>
</body>
</html>
