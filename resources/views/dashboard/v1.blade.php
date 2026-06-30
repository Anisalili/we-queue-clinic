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
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="bi bi-info-circle"></i> Cara Booking Online</h4>
                    </div>
                    <div class="card-body">
                        <p class="mb-3">Ikuti langkah berikut untuk membuat janji kunjungan ke klinik:</p>
                        <ol class="mb-3 ps-3" style="line-height: 1.9;">
                            <li>Buka menu <strong>Booking &rarr; Buat Booking</strong> di sidebar.</li>
                            <li>Pilih <strong>kategori pasien</strong> (BPJS atau Umum).</li>
                            <li>Pilih <strong>tanggal kunjungan</strong> yang tersedia (maksimal 7 hari ke depan).</li>
                            <li>Klik <strong>Booking</strong> untuk mendapatkan <strong>nomor antrian</strong> secara otomatis.</li>
                            <li>Datang ke klinik tepat waktu lalu <strong>check-in di resepsionis</strong> agar masuk ke antrian.</li>
                        </ol>
                        <div class="alert alert-warning mb-0">
                            <i class="bi bi-exclamation-triangle"></i>
                            Anda hanya boleh memiliki <strong>1 booking aktif</strong> dalam satu waktu. Pembatalan mandiri minimal <strong>2 jam</strong> sebelum jadwal.
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('booking.create') }}" class="btn btn-primary"><i class="bi bi-calendar-plus"></i> Buat Booking Sekarang</a>
                        </div>
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
                                <h6 class="font-extrabold mb-0">{{ $activeBookingCount }}</h6>
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
                                <h6 class="font-extrabold mb-0">{{ $totalVisits }}</h6>
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
                        <h4>Status Antrian Hari Ini</h4>
                        <small class="text-muted">Diperbarui {{ now()->format('H:i') }} WITA</small>
                    </div>
                    <div class="card-body">
                        @if($activeBooking)
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-1">Nomor Anda</p>
                                    <h2 class="mb-0 text-primary">{{ $activeBooking->formatted_queue_number }}</h2>
                                    <div class="mt-1">{!! $activeBooking->status_badge !!}</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-1">Sedang Dilayani</p>
                                    <h4 class="mb-0">
                                        {{ $servingNow ? $servingNow->formatted_queue_number : 'Tidak ada' }}
                                    </h4>
                                    <small class="text-muted d-block">
                                        {{ $servingNow ? $servingNow->user->name : 'Belum ada pasien dipanggil' }}
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3 bg-light rounded">
                                    <p class="text-muted mb-1">Sisa Antrian Sebelum Anda</p>
                                    <h4 class="mb-0">{{ $queueAhead ?? 0 }}</h4>
                                    <small class="text-muted d-block">
                                        @if($activeBooking->status === 'booking')
                                            Check-in di resepsionis untuk masuk ke antrian.
                                        @elseif(($queueAhead ?? 0) === 0)
                                            Bersiap, giliran Anda segera dipanggil.
                                        @else
                                            Tunggu hingga nomor Anda dipanggil.
                                        @endif
                                    </small>
                                </div>
                            </div>
                        </div>

                        <hr>

                        <h6 class="mb-3">Antrian Menunggu</h6>
                        @if($upcomingQueue->count() > 0)
                        <div class="row">
                            @foreach($upcomingQueue as $queue)
                            <div class="col-md-4 col-lg-3 mb-3">
                                <div class="border rounded p-3 h-100">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="fw-bold text-primary">{{ $queue->formatted_queue_number }}</span>
                                        @if($queue->patient_category === 'bpjs')
                                            <span class="badge bg-success">BPJS</span>
                                        @else
                                            <span class="badge bg-primary">UMUM</span>
                                        @endif
                                    </div>
                                    <small class="text-muted d-block">{{ $queue->user->name }}</small>
                                    @if($activeBooking && $queue->id === $activeBooking->id)
                                        <small class="text-success fw-semibold">Nomor Anda</small>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="alert alert-light border">Belum ada antrian menunggu.</div>
                        @endif
                        @else
                        <div class="alert alert-info d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <strong>Belum ada booking untuk hari ini.</strong>
                                <div class="text-dark">Buat booking baru untuk mendapatkan nomor antrian.</div>
                            </div>
                            <a href="{{ route('booking.create') }}" class="btn btn-primary btn-sm">Buat Booking</a>
                        </div>
                        @endif
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
                        @if($activeBooking)
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div>
                                    <p class="mb-1">Nomor antrian Anda <strong class="text-primary">{{ $activeBooking->formatted_queue_number }}</strong> untuk tanggal <strong>{{ \Carbon\Carbon::parse($activeBooking->booking_date)->locale('id')->translatedFormat('l, d M Y') }}</strong>.</p>
                                    <small class="text-muted">Status: {!! $activeBooking->status_badge !!}</small>
                                </div>
                                <a href="{{ route('booking.show', $activeBooking) }}" class="btn btn-outline-primary btn-sm">Lihat Detail</a>
                            </div>
                        @else
                            <p class="text-muted mb-0">Belum ada booking. <a href="{{ route('booking.create') }}">Buat booking sekarang</a></p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
