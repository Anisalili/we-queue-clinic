@extends('layouts.app')

@section('title', 'Dashboard Owner')
@section('page-title', 'Dashboard Owner')

@section('content')
<section class="row">
    <div class="col-12 col-lg-9">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Selamat Datang, {{ $user->name }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Dashboard owner untuk monitoring sistem dan analitik klinik.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                                <div class="stats-icon purple mb-2">
                                    <i class="iconly-boldProfile"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                                <h6 class="text-muted font-semibold">Total Pasien</h6>
                                <h6 class="font-extrabold mb-0">{{ $total_patients }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldBookmark"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                                <h6 class="text-muted font-semibold">Booking Bulan Ini</h6>
                                <h6 class="font-extrabold mb-0">{{ $bookings_this_month }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                                <div class="stats-icon green mb-2">
                                    <i class="iconly-boldTicket-Star"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                                <h6 class="text-muted font-semibold">Pasien BPJS (Bulan Ini)</h6>
                                <h6 class="font-extrabold mb-0">{{ $bpjs_count }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-6 col-lg-3 col-md-6">
                <div class="card">
                    <div class="card-body px-4 py-4-5">
                        <div class="row">
                            <div class="col-md-4 col-lg-12 col-xl-12 col-xxl-4 d-flex justify-content-start">
                                <div class="stats-icon red mb-2">
                                    <i class="iconly-boldWallet"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                                <h6 class="text-muted font-semibold">Pasien Umum (Bulan Ini)</h6>
                                <h6 class="font-extrabold mb-0">{{ $umum_count }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Statistik Mingguan (7 Hari Terakhir)</h4>
                    </div>
                    <div class="card-body">
                        @if($weekly_stats->isNotEmpty())
                            <canvas id="weeklyChart" height="80"></canvas>
                        @else
                            <p class="text-muted text-center py-4">Belum ada data booking</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-3">
        <div class="card">
            <div class="card-header">
                <h4>Quick Actions</h4>
            </div>
            <div class="card-content pb-4">
                <div class="px-4">
                    <a href="{{ route('schedules.index') }}" class="btn btn-block btn-xl btn-primary font-bold mt-3">
                        <i class="bi bi-calendar3"></i> Kelola Jadwal
                    </a>
                    <a href="{{ route('report.index') }}" class="btn btn-block btn-xl btn-info font-bold mt-3">
                        <i class="bi bi-bar-chart"></i> Lihat Laporan
                    </a>
                    <a href="{{ route('queue.index') }}" class="btn btn-block btn-xl btn-success font-bold mt-3">
                        <i class="bi bi-people"></i> Monitor Antrian
                    </a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4>System Info</h4>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Jadwal Hari Ini</span>
                    @if($today_schedule['is_closed'])
                        <span class="fw-bold text-danger">Tutup</span>
                    @else
                        <span class="fw-bold">
                            {{ \Carbon\Carbon::parse($today_schedule['start_time'])->format('H:i') }} -
                            {{ \Carbon\Carbon::parse($today_schedule['end_time'])->format('H:i') }}
                        </span>
                    @endif
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Slot Tersedia</span>
                    <span class="fw-bold">{{ $today_slots['used'] }} / {{ $today_slots['max'] }}</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    @if($today_schedule['is_closed'])
                        <span class="badge bg-danger">Tutup</span>
                    @elseif($today_slots['used'] >= $today_slots['max'])
                        <span class="badge bg-warning">Penuh</span>
                    @else
                        <span class="badge bg-success">Aktif</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@if($weekly_stats->isNotEmpty())
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Weekly Stats Chart
    const weeklyData = {
        labels: @json($weekly_stats->pluck('date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M'))),
        datasets: [{
            label: 'Jumlah Booking',
            data: @json($weekly_stats->pluck('total')),
            backgroundColor: 'rgba(67, 94, 190, 0.2)',
            borderColor: '#435ebe',
            borderWidth: 2,
            tension: 0.4,
            fill: true,
        }]
    };

    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'line',
        data: weeklyData,
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
