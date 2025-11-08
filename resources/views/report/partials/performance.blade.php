@php
    $performance = $performance ?? [];
    $kpi = $kpi ?? [];
@endphp

<div class="row">
    <!-- KPI Cards -->
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card success">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Success Rate</h6>
                        <h3 class="mb-0">{{ $kpi['booking_success_rate'] ?? 0 }}%</h3>
                        <small class="text-muted">Pasien selesai dilayani</small>
                    </div>
                    <div class="avatar bg-light-success">
                        <i class="bi bi-check-circle fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card danger">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="text-muted mb-1">Cancel Rate</h6>
                        <h3 class="mb-0">{{ $kpi['cancellation_rate'] ?? 0 }}%</h3>
                        <small class="text-muted">Booking dibatalkan</small>
                    </div>
                    <div class="avatar bg-light-danger">
                        <i class="bi bi-x-circle fs-3"></i>
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
                        <h6 class="text-muted mb-1">No-Show Rate</h6>
                        <h3 class="mb-0">{{ $kpi['no_show_rate'] ?? 0 }}%</h3>
                        <small class="text-muted">Tidak hadir</small>
                    </div>
                    <div class="avatar bg-light-warning">
                        <i class="bi bi-exclamation-triangle fs-3"></i>
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
                        <h6 class="text-muted mb-1">Slot Utilization</h6>
                        <h3 class="mb-0">{{ $kpi['slot_utilization'] ?? 0 }}%</h3>
                        <small class="text-muted">Penggunaan slot</small>
                    </div>
                    <div class="avatar bg-light-info">
                        <i class="bi bi-pie-chart fs-3"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Average Service Time -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Rata-rata Waktu Pelayanan</h5>
            </div>
            <div class="card-body">
                <div class="mb-3 pb-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Overall</span>
                        <h4 class="mb-0 text-primary">{{ $performance['avg_service_time'] ?? 0 }} <small>menit</small></h4>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><span class="badge bg-success">BPJS</span></span>
                        <h5 class="mb-0">{{ $performance['avg_service_time_bpjs'] ?? 0 }} <small>menit</small></h5>
                    </div>
                </div>
                <div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted"><span class="badge bg-primary">Umum</span></span>
                        <h5 class="mb-0">{{ $performance['avg_service_time_umum'] ?? 0 }} <small>menit</small></h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Peak Hours -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Jam Tersibuk (Peak Hours)</h5>
            </div>
            <div class="card-body">
                @if(isset($performance['peak_hours']) && $performance['peak_hours']->isNotEmpty())
                    @foreach($performance['peak_hours'] as $hour => $count)
                        <div class="mb-2">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>{{ sprintf('%02d:00', $hour) }} - {{ sprintf('%02d:00', $hour + 1) }}</span>
                                <strong>{{ $count }} pasien</strong>
                            </div>
                            <div class="progress" style="height: 6px;">
                                @php
                                    $maxCount = $performance['peak_hours']->max();
                                    $percentage = $maxCount > 0 ? ($count / $maxCount) * 100 : 0;
                                @endphp
                                <div class="progress-bar bg-primary" role="progressbar"
                                     style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">Tidak ada data</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Booking Type Breakdown -->
    <div class="col-md-4 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tipe Booking</h5>
            </div>
            <div class="card-body">
                @if(isset($performance['booking_type_breakdown']) && $performance['booking_type_breakdown']->isNotEmpty())
                    @foreach($performance['booking_type_breakdown'] as $type)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span>
                                    @if($type->booking_type == 'online')
                                        <span class="badge bg-info">Online</span>
                                    @else
                                        <span class="badge bg-secondary">Walk-in</span>
                                    @endif
                                </span>
                                <strong>{{ $type->total }} pasien</strong>
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted text-center py-3">Tidak ada data</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Daily Trend Chart -->
    <div class="col-12 mb-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Trend Kunjungan Harian</h5>
            </div>
            <div class="card-body">
                @if(isset($performance['daily_trend']) && $performance['daily_trend']->isNotEmpty())
                    <canvas id="performanceTrendChart" height="80"></canvas>
                @else
                    <p class="text-muted text-center py-4">Tidak ada data untuk periode ini</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if(isset($performance['daily_trend']) && $performance['daily_trend']->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Performance Trend Line Chart
    const performanceTrendData = {
        labels: @json($performance['daily_trend']->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
        datasets: [{
            label: 'Jumlah Pasien',
            data: @json($performance['daily_trend']->pluck('total')),
            borderColor: '#435ebe',
            backgroundColor: 'rgba(67, 94, 190, 0.1)',
            tension: 0.4,
            fill: true,
        }]
    };

    const performanceTrendCtx = document.getElementById('performanceTrendChart').getContext('2d');
    new Chart(performanceTrendCtx, {
        type: 'line',
        data: performanceTrendData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false,
                }
            }
        }
    });
</script>
@endpush
@endif
