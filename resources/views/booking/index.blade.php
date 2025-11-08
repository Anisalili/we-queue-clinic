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
            <div class="card bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-success mb-0">Pasien BPJS</h6>
                            <h3 class="font-extrabold mb-0">{{ $stats['bpjs'] }}</h3>
                        </div>
                        <div class="text-success" style="font-size: 3rem;">
                            <i class="bi bi-shield-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="text-primary mb-0">Pasien Umum</h6>
                            <h3 class="font-extrabold mb-0">{{ $stats['umum'] }}</h3>
                        </div>
                        <div class="text-primary" style="font-size: 3rem;">
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
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Daftar Booking</h4>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
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
                            @foreach($bookings as $booking)
                            <tr>
                                <td>
                                    <h4 class="mb-0 text-primary">{{ $booking->formatted_queue_number }}</h4>
                                </td>
                                <td>
                                    <strong>{{ $booking->user->name }}</strong>
                                    <br><small class="text-muted">{{ $booking->user->phone ?? '-' }}</small>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    <br><small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->translatedFormat('l') }}</small>
                                </td>
                                <td>{!! $booking->category_badge !!}</td>
                                <td>{!! $booking->status_badge !!}</td>
                                <td>
                                    <span class="badge {{ $booking->booking_type === 'online' ? 'bg-info' : 'bg-warning' }}">
                                        {{ ucfirst($booking->booking_type) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('booking.show', $booking) }}"
                                           class="btn btn-sm btn-info"
                                           title="Detail">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if($booking->status === 'booking')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="checkIn{{ $booking->id }}()"
                                                title="Check-in">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                        @endif

                                        @if($booking->status === 'menunggu')
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                onclick="startService{{ $booking->id }}()"
                                                title="Mulai Pelayanan">
                                            <i class="bi bi-play-circle"></i>
                                        </button>
                                        @endif

                                        @if($booking->status === 'berlangsung')
                                        <button type="button"
                                                class="btn btn-sm btn-success"
                                                onclick="finishService{{ $booking->id }}()"
                                                title="Selesai">
                                            <i class="bi bi-check2-circle"></i>
                                        </button>
                                        @endif

                                        @if(in_array($booking->status, ['booking', 'menunggu']))
                                        <button type="button"
                                                class="btn btn-sm btn-danger"
                                                onclick="cancelBooking{{ $booking->id }}()"
                                                title="Batalkan">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                        @endif
                                    </div>

                                    <!-- Hidden Forms -->
                                    @if($booking->status === 'booking')
                                    <form id="checkin-form-{{ $booking->id }}"
                                          action="{{ route('booking.check-in', $booking) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <script>
                                        function checkIn{{ $booking->id }}() {
                                            Swal.fire({
                                                title: 'Check-in Pasien?',
                                                html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#198754',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Ya, Check-in!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('checkin-form-{{ $booking->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                    @endif

                                    @if($booking->status === 'menunggu')
                                    <form id="start-form-{{ $booking->id }}"
                                          action="{{ route('booking.start-service', $booking) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <script>
                                        function startService{{ $booking->id }}() {
                                            Swal.fire({
                                                title: 'Mulai Pelayanan?',
                                                html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#0d6efd',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Ya, Mulai!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('start-form-{{ $booking->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                    @endif

                                    @if($booking->status === 'berlangsung')
                                    <form id="finish-form-{{ $booking->id }}"
                                          action="{{ route('booking.finish-service', $booking) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                    </form>
                                    <script>
                                        function finishService{{ $booking->id }}() {
                                            Swal.fire({
                                                title: 'Selesai Pelayanan?',
                                                html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                                                icon: 'question',
                                                showCancelButton: true,
                                                confirmButtonColor: '#198754',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Ya, Selesai!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('finish-form-{{ $booking->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                    @endif

                                    @if(in_array($booking->status, ['booking', 'menunggu']))
                                    <form id="cancel-form-{{ $booking->id }}"
                                          action="{{ route('booking.cancel', $booking) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        <input type="hidden" name="cancellation_reason" value="Dibatalkan oleh admin">
                                    </form>
                                    <script>
                                        function cancelBooking{{ $booking->id }}() {
                                            Swal.fire({
                                                title: 'Batalkan Booking?',
                                                html: '<strong>{{ $booking->user->name }}</strong><br>Nomor Antrian: {{ $booking->formatted_queue_number }}',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#dc3545',
                                                cancelButtonColor: '#6c757d',
                                                confirmButtonText: 'Ya, Batalkan!',
                                                cancelButtonText: 'Tidak'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('cancel-form-{{ $booking->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $bookings->links() }}
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Tidak ada booking ditemukan untuk filter yang dipilih.
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
