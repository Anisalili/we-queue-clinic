@php
    $summary = $summary ?? [];
@endphp

<div class="row">
    <!-- Statistics Cards -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card primary">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Total Pasien</h6>
                        <h3 class="mb-0">{{ $summary['total_patients'] ?? 0 }}</h3>
                    </div>
                    <div class="avatar bg-light-primary">
                        <i class="bi bi-people fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pasien BPJS</h6>
                        <h3 class="mb-0">{{ $summary['bpjs_count'] ?? 0 }}</h3>
                        <small class="text-success">{{ $summary['bpjs_percentage'] ?? 0 }}%</small>
                    </div>
                    <div class="avatar bg-light-success">
                        <i class="bi bi-heart-pulse fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card info">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Pasien Umum</h6>
                        <h3 class="mb-0">{{ $summary['umum_count'] ?? 0 }}</h3>
                        <small class="text-info">{{ $summary['umum_percentage'] ?? 0 }}%</small>
                    </div>
                    <div class="avatar bg-light-info">
                        <i class="bi bi-wallet2 fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card warning">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Rata-rata/Hari</h6>
                        <h3 class="mb-0">{{ $summary['avg_per_day'] ?? 0 }}</h3>
                    </div>
                    <div class="avatar bg-light-warning">
                        <i class="bi bi-graph-up fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Status Breakdown -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Status Booking</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <tbody>
                            <tr>
                                <td><span class="badge bg-warning">Booking</span></td>
                                <td class="text-end">{{ $summary['status_breakdown']['booking'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info">Menunggu</span></td>
                                <td class="text-end">{{ $summary['status_breakdown']['menunggu'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-primary">Berlangsung</span></td>
                                <td class="text-end">{{ $summary['status_breakdown']['berlangsung'] ?? 0 }}</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Selesai</span></td>
                                <td class="text-end"><strong>{{ $summary['status_breakdown']['selesai'] ?? 0 }}</strong></td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-danger">Batal</span></td>
                                <td class="text-end">{{ $summary['status_breakdown']['batal'] ?? 0 }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Metrics -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Metrics</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Tingkat Pembatalan</span>
                        <strong class="text-danger">{{ $summary['cancellation_rate'] ?? 0 }}%</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-danger" role="progressbar"
                             style="width: {{ min($summary['cancellation_rate'] ?? 0, 100) }}%"></div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Tingkat No-Show</span>
                        <strong class="text-warning">{{ $summary['no_show_rate'] ?? 0 }}%</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-warning" role="progressbar"
                             style="width: {{ min($summary['no_show_rate'] ?? 0, 100) }}%"></div>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="text-muted">Completion Rate</span>
                        @php
                            $total = ($summary['total_patients'] ?? 0);
                            $completionRate = $total > 0 ? round((($summary['total_completed'] ?? 0) / $total) * 100, 2) : 0;
                        @endphp
                        <strong class="text-success">{{ $completionRate }}%</strong>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar bg-success" role="progressbar"
                             style="width: {{ $completionRate }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Category Chart -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Distribusi Kategori Pasien</h5>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Daily Trend Chart -->
    <div class="col-md-6 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Trend Harian</h5>
            </div>
            <div class="card-body">
                <canvas id="dailyTrendChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Category Pie Chart
    const categoryChartData = @json($category_chart ?? ['labels' => [], 'data' => []]);
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryChartData.labels,
            datasets: [{
                data: categoryChartData.data,
                backgroundColor: categoryChartData.colors || ['#198754', '#0d6efd'],
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });

    // Daily Trend Bar Chart
    const dailyTrendData = @json($daily_trend_chart ?? ['labels' => [], 'datasets' => []]);
    const dailyTrendCtx = document.getElementById('dailyTrendChart').getContext('2d');
    new Chart(dailyTrendCtx, {
        type: 'bar',
        data: {
            labels: dailyTrendData.labels,
            datasets: dailyTrendData.datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                x: {
                    stacked: true,
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            plugins: {
                legend: {
                    position: 'bottom',
                }
            }
        }
    });
</script>
@endpush
