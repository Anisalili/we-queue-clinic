@extends('layouts.app')

@section('title', 'Dashboard Pasien')
@section('page-title', 'Dashboard Pasien')

@section('content')
<section class="row">
    <div class="col-12 col-lg-12">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Selamat Datang, {{ $user->name }}</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Ini adalah dashboard pasien. Anda dapat melihat booking dan status antrian Anda di sini.</p>
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
                                <div class="stats-icon blue mb-2">
                                    <i class="iconly-boldCalendar"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                                <h6 class="text-muted font-semibold">Booking Aktif</h6>
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
                                    <i class="iconly-boldTicket"></i>
                                </div>
                            </div>
                            <div class="col-md-8 col-lg-12 col-xl-12 col-xxl-8">
                                <h6 class="text-muted font-semibold">Total Kunjungan</h6>
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
                        <h4>Booking Saya</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Belum ada booking. <a href="{{ route('booking.create') }}">Buat booking sekarang</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
