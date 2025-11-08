@extends('layouts.app')

@section('title', 'Tambah Hari Libur')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Tambah Hari Libur</h3>
                <p class="text-subtitle text-muted">Daftarkan hari libur baru</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.holidays') }}">Hari Libur</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Tambah</li>
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
                        <h4 class="card-title">Form Hari Libur</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('schedules.holidays.store') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                       id="date" name="date" value="{{ old('date') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Hari Libur <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}"
                                       placeholder="Contoh: Hari Kemerdekaan RI" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="type" class="form-label">Tipe Libur <span class="text-danger">*</span></label>
                                <select class="form-select @error('type') is-invalid @enderror"
                                        id="type" name="type" required>
                                    <option value="">-- Pilih Tipe --</option>
                                    <option value="national" {{ old('type') === 'national' ? 'selected' : '' }}>
                                        Hari Libur Nasional
                                    </option>
                                    <option value="clinic_leave" {{ old('type') === 'clinic_leave' ? 'selected' : '' }}>
                                        Cuti Klinik
                                    </option>
                                    <option value="emergency" {{ old('type') === 'emergency' ? 'selected' : '' }}>
                                        Tutup Darurat/Emergency
                                    </option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Keterangan</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3"
                                          placeholder="Keterangan tambahan (opsional)">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('schedules.holidays') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save"></i> Simpan Hari Libur
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-lightbulb"></i> Panduan</h5>
                        <hr>

                        <p><strong>Tipe Hari Libur:</strong></p>

                        <div class="mb-3">
                            <span class="badge bg-primary mb-1">Hari Libur Nasional</span>
                            <p class="small mb-0">Untuk hari libur resmi pemerintah (Tahun Baru, Lebaran, dll)</p>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-warning mb-1">Cuti Klinik</span>
                            <p class="small mb-0">Untuk cuti dokter yang sudah direncanakan</p>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-danger mb-1">Tutup Darurat</span>
                            <p class="small mb-0">Untuk penutupan mendadak (sakit, emergency, dll)</p>
                        </div>

                        <hr>

                        <div class="alert alert-info mb-0">
                            <small>
                                <i class="bi bi-info-circle"></i>
                                <strong>Catatan:</strong> Hari libur akan menutup sistem booking untuk tanggal yang dipilih.
                            </small>
                        </div>
                    </div>
                </div>

                <div class="card bg-warning bg-opacity-10 mt-3">
                    <div class="card-body">
                        <h6><i class="bi bi-exclamation-triangle text-warning"></i> Perhatian</h6>
                        <p class="small mb-0">Pastikan tidak ada booking aktif sebelum menambah hari libur. Jika ada, hubungi pasien untuk reschedule.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
