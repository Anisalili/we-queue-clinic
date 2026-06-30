@extends('layouts.app')

@section('title', 'Kelola Booking')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kelola Semua Booking</h3>
                <p class="text-subtitle text-muted">Monitor dan kelola booking pasien</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Booking</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted font-semibold mb-0">Total Hari Ini</h6>
                            <h4 class="font-extrabold mb-0">{{ $stats['total'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted font-semibold mb-0">Booking</h6>
                            <h4 class="font-extrabold mb-0 text-warning">{{ $stats['booking'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted font-semibold mb-0">Menunggu</h6>
                            <h4 class="font-extrabold mb-0 text-info">{{ $stats['menunggu'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted font-semibold mb-0">Berlangsung</h6>
                            <h4 class="font-extrabold mb-0 text-primary">{{ $stats['berlangsung'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted font-semibold mb-0">Selesai</h6>
                            <h4 class="font-extrabold mb-0 text-success">{{ $stats['selesai'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-2 col-md-4">
            <div class="card">
                <div class="card-body px-3 py-4">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="text-muted font-semibold mb-0">Batal</h6>
                            <h4 class="font-extrabold mb-0 text-danger">{{ $stats['batal'] }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Stats -->
    <div class="row mb-4">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted font-semibold mb-0">Pasien BPJS</h6>
                            <h3 class="font-extrabold mb-0">{{ $stats['bpjs'] }}</h3>
                        </div>
                        <div class="stats-icon green">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted font-semibold mb-0">Pasien Umum</h6>
                            <h3 class="font-extrabold mb-0">{{ $stats['umum'] }}</h3>
                        </div>
                        <div class="stats-icon blue">
                            <i class="bi bi-wallet2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <section class="section">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('booking.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="date" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="date" name="date"
                               value="{{ request('date') ?? today()->format('Y-m-d') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="booking" {{ request('status') === 'booking' ? 'selected' : '' }}>Booking</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="berlangsung" {{ request('status') === 'berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="category" class="form-label">Kategori</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Semua Kategori</option>
                            <option value="bpjs" {{ request('category') === 'bpjs' ? 'selected' : '' }}>BPJS</option>
                            <option value="umum" {{ request('category') === 'umum' ? 'selected' : '' }}>Umum</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="search" class="form-label">Cari Pasien</label>
                        <input type="text" class="form-control" id="search" name="search"
                               placeholder="Nama pasien..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <!-- Booking List -->
    @php
        $bpjsBookings = $bookings->where('patient_category', 'bpjs');
        $umumBookings = $bookings->where('patient_category', 'umum');
    @endphp
    <section class="section">
        @if($bookings->count() > 0)
        <div class="row">
            <!-- Daftar Booking BPJS -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title mb-0"><span class="badge bg-success me-2">BPJS</span> Daftar Booking BPJS ({{ $bpjsBookings->count() }})</h4>
                        <small class="text-muted">Nomor antrian BPJS bisa diubah agar sinkron dengan Mobile JKN</small>
                    </div>
                    <div class="card-body">
                        @if($bpjsBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Antrian</th>
                                        <th>Pasien</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($bpjsBookings as $booking)
                                        @include('booking.partials.row', ['booking' => $booking])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-light border mb-0">
                            <i class="bi bi-info-circle"></i> Tidak ada booking BPJS untuk filter yang dipilih.
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Daftar Booking Umum -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title mb-0"><span class="badge bg-primary me-2">UMUM</span> Daftar Booking Umum ({{ $umumBookings->count() }})</h4>
                    </div>
                    <div class="card-body">
                        @if($umumBookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>No. Antrian</th>
                                        <th>Pasien</th>
                                        <th>Tanggal</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Tipe</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($umumBookings as $booking)
                                        @include('booking.partials.row', ['booking' => $booking])
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <div class="alert alert-light border mb-0">
                            <i class="bi bi-info-circle"></i> Tidak ada booking Umum untuk filter yang dipilih.
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-3">
            {{ $bookings->links() }}
        </div>
        @else
        <div class="card">
            <div class="card-body">
                <div class="alert alert-info mb-0">
                    <i class="bi bi-info-circle"></i> Tidak ada booking ditemukan untuk filter yang dipilih.
                </div>
            </div>
        </div>
        @endif
    </section>
</div>
@endsection
