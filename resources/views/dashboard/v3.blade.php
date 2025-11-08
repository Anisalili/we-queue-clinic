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
                                <h6 class="font-extrabold mb-0">0</h6>
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
                                <h6 class="font-extrabold mb-0">0</h6>
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
                                <h6 class="text-muted font-semibold">Pasien BPJS</h6>
                                <h6 class="font-extrabold mb-0">0</h6>
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
                                <h6 class="text-muted font-semibold">Pasien Umum</h6>
                                <h6 class="font-extrabold mb-0">0</h6>
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
                        <h4>Statistik Mingguan</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted text-center py-4">Grafik akan ditampilkan di sini</p>
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
                    <span class="fw-bold">08:00 - 15:00</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Slot Tersedia</span>
                    <span class="fw-bold">30 / 30</span>
                </div>
                <div class="d-flex justify-content-between">
                    <span class="text-muted">Status</span>
                    <span class="badge bg-success">Aktif</span>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
