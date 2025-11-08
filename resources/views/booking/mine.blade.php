@extends('layouts.app')

@section('title', 'Booking Saya')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Booking Saya</h3>
                <p class="text-subtitle text-muted">Daftar booking aktif dan riwayat</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Booking Saya</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <!-- Quick Action -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Ingin booking lagi?</h5>
                            <small class="text-muted">Buat booking baru untuk kunjungan Anda</small>
                        </div>
                        <a href="{{ route('booking.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Buat Booking Baru
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Bookings -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Booking Aktif</h4>
            </div>
            <div class="card-body">
                @if($activeBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor Antrian</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeBookings as $booking)
                            <tr>
                                <td>
                                    <h4 class="mb-0 text-primary">{{ $booking->formatted_queue_number }}</h4>
                                </td>
                                <td>
                                    <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->translatedFormat('l, d M Y') }}</strong>
                                    <br>
                                    <small class="text-muted">Dibuat: {{ $booking->created_at->format('d M Y, H:i') }}</small>
                                </td>
                                <td>{!! $booking->category_badge !!}</td>
                                <td>{!! $booking->status_badge !!}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('booking.show', $booking) }}" class="btn btn-sm btn-info">
                                            <i class="bi bi-eye"></i> Detail
                                        </a>
                                        @if($booking->status === 'booking' && $booking->can_cancel)
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmCancel{{ $booking->id }}()">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </button>

                                        <form id="cancel-form-{{ $booking->id }}"
                                              action="{{ route('booking.cancel', $booking) }}"
                                              method="POST" class="d-none">
                                            @csrf
                                            <input type="hidden" name="cancellation_reason" value="Dibatalkan oleh pasien">
                                        </form>

                                        <script>
                                            function confirmCancel{{ $booking->id }}() {
                                                Swal.fire({
                                                    title: 'Batalkan Booking?',
                                                    html: 'Booking untuk tanggal <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</strong> akan dibatalkan.<br><small class="text-muted">Nomor antrian: {{ $booking->formatted_queue_number }}</small>',
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonColor: '#d33',
                                                    cancelButtonColor: '#3085d6',
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
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Anda tidak memiliki booking aktif.
                    <a href="{{ route('booking.create') }}" class="alert-link">Buat booking baru</a>
                </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Booking History -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Riwayat Booking</h4>
            </div>
            <div class="card-body">
                @if($historyBookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nomor Antrian</th>
                                <th>Tanggal</th>
                                <th>Kategori</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($historyBookings as $booking)
                            <tr>
                                <td>
                                    <span class="text-muted">{{ $booking->formatted_queue_number }}</span>
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ \Carbon\Carbon::parse($booking->booking_date)->diffForHumans() }}</small>
                                </td>
                                <td>{!! $booking->category_badge !!}</td>
                                <td>{!! $booking->status_badge !!}</td>
                                <td>
                                    <a href="{{ route('booking.show', $booking) }}" class="btn btn-sm btn-outline-info">
                                        <i class="bi bi-eye"></i> Lihat
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $historyBookings->links() }}
                </div>
                @else
                <div class="alert alert-secondary">
                    <i class="bi bi-inbox"></i> Belum ada riwayat booking
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
