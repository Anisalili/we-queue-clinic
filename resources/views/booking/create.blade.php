@extends('layouts.app')

@section('title', 'Buat Booking')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Buat Booking Online</h3>
                <p class="text-subtitle text-muted">Pilih tanggal dan kategori untuk booking</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('booking.mine') }}">Booking Saya</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Buat Booking</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-12 col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Form Booking</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('booking.store') }}" method="POST" id="bookingForm">
                            @csrf

                            <div class="mb-4">
                                <label for="booking_date" class="form-label fw-bold">Pilih Tanggal <span class="text-danger">*</span></label>
                                <div class="row">
                                    @foreach($availableDates as $dateInfo)
                                    <div class="col-12 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input date-radio"
                                                   type="radio"
                                                   name="booking_date"
                                                   id="date-{{ $loop->index }}"
                                                   value="{{ $dateInfo['date'] }}"
                                                   data-can-book="{{ $dateInfo['can_book'] ? '1' : '0' }}"
                                                   data-slots="{{ $dateInfo['available_slots'] ?? 0 }}"
                                                   {{ !$dateInfo['can_book'] ? 'disabled' : '' }}
                                                   {{ old('booking_date') === $dateInfo['date'] ? 'checked' : '' }}>
                                            <label class="form-check-label w-100" for="date-{{ $loop->index }}">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div>
                                                        <strong>{{ $dateInfo['formatted_date'] }}</strong>
                                                        @if(!$dateInfo['can_book'])
                                                            <br><small class="text-danger">{{ $dateInfo['reason'] }}</small>
                                                        @else
                                                            <br><small class="text-success">Tersedia {{ $dateInfo['available_slots'] }} slot</small>
                                                        @endif
                                                    </div>
                                                    @if($dateInfo['can_book'])
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @else
                                                        <span class="badge bg-secondary">Tidak Tersedia</span>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @error('booking_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="patient_category" class="form-label fw-bold">Kategori Pasien <span class="text-danger">*</span></label>
                                <div class="form-check mb-2">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="patient_category"
                                           id="category-bpjs"
                                           value="bpjs"
                                           {{ old('patient_category') === 'bpjs' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category-bpjs">
                                        <span class="badge bg-success me-2">BPJS</span> Pasien dengan jaminan BPJS Kesehatan
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="patient_category"
                                           id="category-umum"
                                           value="umum"
                                           {{ old('patient_category') === 'umum' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category-umum">
                                        <span class="badge bg-primary me-2">Umum</span> Pasien dengan pembayaran mandiri
                                    </label>
                                </div>
                                <small class="text-muted">Antrian BPJS dan Umum digabung (FIFO)</small>
                                @error('patient_category')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle"></i> Informasi Penting</h6>
                                <ul class="mb-0 small">
                                    <li>Anda hanya dapat memiliki 1 booking aktif</li>
                                    <li>Booking dapat dilakukan maksimal 7 hari ke depan</li>
                                    <li>Pembatalan minimal 2 jam sebelum jadwal booking</li>
                                    <li>Harap datang tepat waktu sesuai nomor antrian</li>
                                </ul>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('booking.mine') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="bi bi-calendar-check"></i> Buat Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-lightbulb"></i> Panduan Booking</h5>
                        <hr>

                        <p class="small"><strong>Langkah-langkah:</strong></p>
                        <ol class="small">
                            <li>Pilih tanggal yang tersedia</li>
                            <li>Pilih kategori pasien (BPJS/Umum)</li>
                            <li>Klik "Buat Booking"</li>
                            <li>Simpan nomor antrian Anda</li>
                            <li>Datang tepat waktu di hari booking</li>
                        </ol>

                        <hr>

                        <div class="alert alert-warning mb-0">
                            <small>
                                <strong>Perhatian:</strong> Jika Anda tidak datang tanpa konfirmasi, booking akan dibatalkan otomatis.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-clock-history"></i> Jam Praktik</h6>
                        <hr>
                        <p class="small mb-1"><strong>Senin - Sabtu:</strong></p>
                        <p class="small">08:00 - 15:00 WIB</p>
                        <p class="small mb-0 text-danger"><strong>Minggu:</strong> Tutup</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Form validation
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        const dateChecked = document.querySelector('input[name="booking_date"]:checked');
        const categoryChecked = document.querySelector('input[name="patient_category"]:checked');

        if (!dateChecked) {
            e.preventDefault();
            Toastify({
                text: "Pilih tanggal booking!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
            }).showToast();
            return false;
        }

        if (!categoryChecked) {
            e.preventDefault();
            Toastify({
                text: "Pilih kategori pasien!",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545",
            }).showToast();
            return false;
        }
    });
</script>
@endsection
