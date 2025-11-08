@extends('layouts.app')

@section('title', 'Edit Override Jadwal')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Override Jadwal</h3>
                <p class="text-subtitle text-muted">Ubah jadwal khusus untuk tanggal {{ Carbon\Carbon::parse($override->date)->format('d M Y') }}</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.overrides') }}">Override</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
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
                        <h4 class="card-title">Form Edit Override</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('schedules.overrides.update', $override) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="mb-3">
                                <label for="date" class="form-label">Tanggal <span class="text-danger">*</span></label>
                                <input type="date" class="form-control @error('date') is-invalid @enderror"
                                       id="date" name="date" value="{{ old('date', $override->date) }}"
                                       min="{{ date('Y-m-d') }}" required>
                                @error('date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status Klinik <span class="text-danger">*</span></label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox"
                                           id="is_closed" name="is_closed" value="1"
                                           {{ old('is_closed', $override->is_closed) ? 'checked' : '' }}
                                           onchange="toggleOverrideFields(this)">
                                    <label class="form-check-label" for="is_closed">
                                        <strong class="text-danger">Klinik Tutup Total</strong>
                                    </label>
                                </div>
                                <small class="text-muted">Centang jika klinik tutup untuk tanggal ini</small>
                            </div>

                            <div id="overrideFields">
                                <div class="alert alert-info">
                                    <i class="bi bi-info-circle"></i>
                                    Kosongkan field untuk menggunakan nilai dari jadwal default
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="start_time" class="form-label">Jam Mulai</label>
                                        <input type="time" class="form-control @error('start_time') is-invalid @enderror"
                                               id="start_time" name="start_time"
                                               value="{{ old('start_time', $override->start_time ? Carbon\Carbon::parse($override->start_time)->format('H:i') : '') }}">
                                        @error('start_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="end_time" class="form-label">Jam Selesai</label>
                                        <input type="time" class="form-control @error('end_time') is-invalid @enderror"
                                               id="end_time" name="end_time"
                                               value="{{ old('end_time', $override->end_time ? Carbon\Carbon::parse($override->end_time)->format('H:i') : '') }}">
                                        @error('end_time')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="max_slots" class="form-label">Kuota Slot</label>
                                    <input type="number" class="form-control @error('max_slots') is-invalid @enderror"
                                           id="max_slots" name="max_slots" value="{{ old('max_slots', $override->max_slots) }}"
                                           min="1" max="100">
                                    <small class="text-muted">Maksimal pasien untuk tanggal ini</small>
                                    @error('max_slots')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="reason" class="form-label">Alasan Override <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('reason') is-invalid @enderror"
                                          id="reason" name="reason" rows="3" required>{{ old('reason', $override->reason) }}</textarea>
                                <small class="text-muted">Contoh: Jadwal singkat, Cuti sebagian hari, dll</small>
                                @error('reason')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('schedules.overrides') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-save"></i> Update Override
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-4">
                <div class="card bg-light">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-info-circle"></i> Informasi</h5>
                        <hr>
                        <table class="table table-sm">
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ Carbon\Carbon::parse($override->date)->format('d M Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Hari:</strong></td>
                                <td>{{ Carbon\Carbon::parse($override->date)->locale('id')->translatedFormat('l') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat:</strong></td>
                                <td>{{ $override->created_at->format('d M Y H:i') }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    function toggleOverrideFields(checkbox) {
        const fields = document.getElementById('overrideFields');
        fields.style.display = checkbox.checked ? 'none' : 'block';

        // Disable/enable fields
        const inputs = fields.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            if (input.id !== 'reason') {
                input.disabled = checkbox.checked;
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        const checkbox = document.getElementById('is_closed');
        toggleOverrideFields(checkbox);
    });
</script>
@endsection
