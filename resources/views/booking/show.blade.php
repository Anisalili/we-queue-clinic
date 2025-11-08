@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Detail Booking</h3>
                <p class="text-subtitle text-muted">Informasi lengkap booking Anda</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('booking.mine') }}">Booking Saya</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Detail</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                <!-- Booking Success Alert -->
                <div class="card border-success">
                    <div class="card-body text-center py-5">
                        <div class="mb-3">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h3 class="text-success mb-3">Booking Berhasil!</h3>
                        <p class="text-muted">Booking Anda telah dikonfirmasi. Simpan nomor antrian di bawah ini.</p>
                    </div>
                </div>

                <!-- Booking Details Card -->
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0 text-white">Informasi Booking</h4>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 text-center mb-4">
                                <div class="p-4 bg-light rounded">
                                    <p class="text-muted mb-1">Nomor Antrian Anda</p>
                                    <h1 class="display-1 fw-bold text-primary mb-0">{{ $booking->formatted_queue_number }}</h1>
                                </div>
                            </div>
                        </div>

                        <table class="table table-borderless">
                            <tr>
                                <td width="40%"><strong>ID Booking</strong></td>
                                <td>: #{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Nama Pasien</strong></td>
                                <td>: {{ $booking->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Booking</strong></td>
                                <td>: {{ \Carbon\Carbon::parse($booking->booking_date)->locale('id')->translatedFormat('l, d F Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kategori Pasien</strong></td>
                                <td>: {!! $booking->category_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Status</strong></td>
                                <td>: {!! $booking->status_badge !!}</td>
                            </tr>
                            <tr>
                                <td><strong>Tipe Booking</strong></td>
                                <td>: <span class="badge bg-info">{{ ucfirst($booking->booking_type) }}</span></td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat</strong></td>
                                <td>: {{ $booking->created_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            @if($booking->check_in_time)
                            <tr>
                                <td><strong>Check-in</strong></td>
                                <td>: {{ $booking->check_in_time->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            @endif
                            @if($booking->service_start_time)
                            <tr>
                                <td><strong>Mulai Pelayanan</strong></td>
                                <td>: {{ $booking->service_start_time->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            @endif
                            @if($booking->service_end_time)
                            <tr>
                                <td><strong>Selesai Pelayanan</strong></td>
                                <td>: {{ $booking->service_end_time->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            @endif
                            @if($booking->cancelled_at)
                            <tr>
                                <td><strong>Dibatalkan</strong></td>
                                <td>: {{ $booking->cancelled_at->format('d M Y, H:i') }} WIB</td>
                            </tr>
                            <tr>
                                <td><strong>Alasan Pembatalan</strong></td>
                                <td>: {{ $booking->cancellation_reason }}</td>
                            </tr>
                            @endif
                        </table>

                        <hr>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('booking.mine') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali ke Booking Saya
                            </a>

                            @if($booking->status === 'booking' && $booking->can_cancel)
                            <button type="button" class="btn btn-danger" onclick="confirmCancel()">
                                <i class="bi bi-x-circle"></i> Batalkan Booking
                            </button>
                            @endif
                        </div>

                        @if($booking->status === 'booking' && $booking->can_cancel)
                        <form id="cancel-form" action="{{ route('booking.cancel', $booking) }}" method="POST" class="d-none">
                            @csrf
                            <input type="hidden" name="cancellation_reason" id="cancellation_reason" value="Dibatalkan oleh pasien">
                        </form>

                        <script>
                            function confirmCancel() {
                                Swal.fire({
                                    title: 'Batalkan Booking?',
                                    html: 'Booking untuk tanggal <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</strong> akan dibatalkan.',
                                    icon: 'warning',
                                    showCancelButton: true,
                                    confirmButtonColor: '#d33',
                                    cancelButtonColor: '#3085d6',
                                    confirmButtonText: 'Ya, Batalkan!',
                                    cancelButtonText: 'Tidak'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        document.getElementById('cancel-form').submit();
                                    }
                                });
                            }
                        </script>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card bg-info bg-opacity-10">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-info-circle"></i> Apa Selanjutnya?</h5>
                        <hr>

                        @if($booking->status === 'booking')
                        <div class="alert alert-warning">
                            <small><strong>Status: Booking</strong><br>
                            Anda sudah melakukan booking. Harap datang di tanggal yang ditentukan.</small>
                        </div>

                        <p class="small"><strong>Yang Harus Dilakukan:</strong></p>
                        <ol class="small">
                            <li>Simpan nomor antrian Anda</li>
                            <li>Datang di tanggal: <strong>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d M Y') }}</strong></li>
                            <li>Check-in di resepsionis</li>
                            <li>Tunggu hingga nomor antrian Anda dipanggil</li>
                        </ol>

                        <hr>

                        <p class="small mb-1"><strong>Bisa membatalkan?</strong></p>
                        <p class="small">
                            @if($booking->can_cancel)
                                <span class="text-success">✓ Ya, Anda masih bisa membatalkan</span><br>
                                <small class="text-muted">Minimal 2 jam sebelum jadwal</small>
                            @else
                                <span class="text-danger">✗ Tidak, sudah melewati batas waktu</span><br>
                                <small class="text-muted">Hubungi klinik jika ingin membatalkan</small>
                            @endif
                        </p>
                        @endif

                        @if($booking->status === 'menunggu')
                        <div class="alert alert-info">
                            <small><strong>Status: Menunggu</strong><br>
                            Anda sudah check-in. Silakan tunggu di ruang tunggu.</small>
                        </div>
                        @endif

                        @if($booking->status === 'berlangsung')
                        <div class="alert alert-primary">
                            <small><strong>Status: Berlangsung</strong><br>
                            Anda sedang dalam proses pelayanan.</small>
                        </div>
                        @endif

                        @if($booking->status === 'selesai')
                        <div class="alert alert-success">
                            <small><strong>Status: Selesai</strong><br>
                            Pelayanan telah selesai. Terima kasih!</small>
                        </div>
                        @endif

                        @if($booking->status === 'batal')
                        <div class="alert alert-danger">
                            <small><strong>Status: Dibatalkan</strong><br>
                            Booking ini telah dibatalkan.</small>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h6><i class="bi bi-telephone"></i> Kontak Klinik</h6>
                        <hr>
                        <p class="small mb-1"><strong>Telepon:</strong></p>
                        <p class="small">0812-3456-7890</p>
                        <p class="small mb-1"><strong>WhatsApp:</strong></p>
                        <p class="small mb-0">0812-3456-7890</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
