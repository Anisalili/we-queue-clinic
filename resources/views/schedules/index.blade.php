@extends('layouts.app')

@section('title', 'Konfigurasi Jadwal')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Konfigurasi Jadwal Praktik</h3>
                <p class="text-subtitle text-muted">Kelola jadwal default, override, dan hari libur</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Jadwal</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('schedules.overrides') }}" class="btn btn-primary">
                            <i class="bi bi-calendar-event"></i> Kelola Override
                        </a>
                        <a href="{{ route('schedules.holidays') }}" class="btn btn-success">
                            <i class="bi bi-calendar-x"></i> Kelola Hari Libur
                        </a>
                        <a href="{{ route('schedules.overrides.create') }}" class="btn btn-outline-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Override
                        </a>
                        <a href="{{ route('schedules.holidays.create') }}" class="btn btn-outline-success">
                            <i class="bi bi-plus-circle"></i> Tambah Hari Libur
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Default Schedule Table --}}
    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Jadwal Default Praktik</h4>
                <p class="text-muted mb-0">Jadwal mingguan yang akan digunakan jika tidak ada override</p>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Hari</th>
                                <th>Status</th>
                                <th>Jam Mulai</th>
                                <th>Jam Selesai</th>
                                <th>Kuota Slot</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                            <tr>
                                <td><strong>{{ $schedule->day_name }}</strong></td>
                                <td>
                                    @if($schedule->is_active)
                                        <span class="badge bg-success">Aktif</span>
                                    @else
                                        <span class="badge bg-secondary">Tutup</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->is_active && $schedule->start_time)
                                        {{ Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->is_active && $schedule->end_time)
                                        {{ Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($schedule->is_active)
                                        <span class="badge bg-info">{{ $schedule->max_slots }} pasien</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-primary"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editModal{{ $schedule->id }}">
                                        <i class="bi bi-pencil"></i> Edit
                                    </button>
                                </td>
                            </tr>

                            {{-- Edit Modal --}}
                            <div class="modal fade" id="editModal{{ $schedule->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Jadwal {{ $schedule->day_name }}</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('schedules.update-default', $schedule) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Status Hari</label>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input" type="checkbox"
                                                               name="is_active" value="1"
                                                               id="isActive{{ $schedule->id }}"
                                                               {{ $schedule->is_active ? 'checked' : '' }}
                                                               onchange="toggleScheduleFields{{ $schedule->id }}(this)">
                                                        <label class="form-check-label" for="isActive{{ $schedule->id }}">
                                                            Klinik Buka
                                                        </label>
                                                    </div>
                                                </div>

                                                <div id="scheduleFields{{ $schedule->id }}">
                                                    <div class="row">
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                                            <input type="time" class="form-control" name="start_time"
                                                                   value="{{ $schedule->start_time ? Carbon\Carbon::parse($schedule->start_time)->format('H:i') : '' }}"
                                                                   required>
                                                        </div>
                                                        <div class="col-md-6 mb-3">
                                                            <label class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                                            <input type="time" class="form-control" name="end_time"
                                                                   value="{{ $schedule->end_time ? Carbon\Carbon::parse($schedule->end_time)->format('H:i') : '' }}"
                                                                   required>
                                                        </div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label">Kuota Slot <span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" name="max_slots"
                                                               value="{{ $schedule->max_slots }}"
                                                               min="1" max="100" required>
                                                        <small class="text-muted">Maksimal pasien per hari</small>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <script>
                                function toggleScheduleFields{{ $schedule->id }}(checkbox) {
                                    const fields = document.getElementById('scheduleFields{{ $schedule->id }}');
                                    fields.style.display = checkbox.checked ? 'block' : 'none';
                                }
                                // Initialize on page load
                                document.addEventListener('DOMContentLoaded', function() {
                                    const checkbox = document.getElementById('isActive{{ $schedule->id }}');
                                    toggleScheduleFields{{ $schedule->id }}(checkbox);
                                });
                            </script>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    {{-- Upcoming Overrides --}}
    @if($upcomingOverrides->count() > 0)
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">Override Mendatang</h4>
                    <p class="text-muted mb-0">Jadwal khusus yang akan diterapkan</p>
                </div>
                <a href="{{ route('schedules.overrides') }}" class="btn btn-sm btn-primary">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jam</th>
                                <th>Slot</th>
                                <th>Alasan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingOverrides as $override)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($override->date)->format('d M Y') }}</td>
                                <td>
                                    @if($override->is_closed)
                                        <span class="badge bg-danger">Tutup</span>
                                    @else
                                        <span class="badge bg-warning">Override</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$override->is_closed && $override->start_time)
                                        {{ Carbon\Carbon::parse($override->start_time)->format('H:i') }} - {{ Carbon\Carbon::parse($override->end_time)->format('H:i') }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$override->is_closed && $override->max_slots)
                                        {{ $override->max_slots }} pasien
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td><small>{{ Str::limit($override->reason, 50) }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Upcoming Holidays --}}
    @if($upcomingHolidays->count() > 0)
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="card-title mb-0">Hari Libur Mendatang</h4>
                </div>
                <a href="{{ route('schedules.holidays') }}" class="btn btn-sm btn-success">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Nama Libur</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upcomingHolidays as $holiday)
                            <tr>
                                <td>{{ Carbon\Carbon::parse($holiday->date)->format('d M Y') }}</td>
                                <td><strong>{{ $holiday->name }}</strong></td>
                                <td>
                                    @if($holiday->type === 'national')
                                        <span class="badge bg-primary">Nasional</span>
                                    @elseif($holiday->type === 'clinic_leave')
                                        <span class="badge bg-warning">Cuti Klinik</span>
                                    @else
                                        <span class="badge bg-danger">Emergency</span>
                                    @endif
                                </td>
                                <td><small>{{ $holiday->description ?? '-' }}</small></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    @endif
</div>
@endsection
