@extends('layouts.app')

@section('title', 'Kelola Override Jadwal')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kelola Override Jadwal</h3>
                <p class="text-subtitle text-muted">Atur jadwal khusus untuk tanggal tertentu</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Override</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Daftar Override</h4>
                <a href="{{ route('schedules.overrides.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Tambah Override
                </a>
            </div>
            <div class="card-body">
                @if($overrides->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Status</th>
                                <th>Jam Praktik</th>
                                <th>Kuota Slot</th>
                                <th>Alasan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($overrides as $override)
                            <tr>
                                <td>
                                    <strong>{{ Carbon\Carbon::parse($override->date)->format('d M Y') }}</strong>
                                </td>
                                <td>{{ Carbon\Carbon::parse($override->date)->locale('id')->translatedFormat('l') }}</td>
                                <td>
                                    @if($override->is_closed)
                                        <span class="badge bg-danger">Tutup</span>
                                    @else
                                        <span class="badge bg-warning">Override</span>
                                    @endif
                                </td>
                                <td>
                                    @if($override->is_closed)
                                        <span class="text-muted">-</span>
                                    @elseif($override->start_time && $override->end_time)
                                        {{ Carbon\Carbon::parse($override->start_time)->format('H:i') }} -
                                        {{ Carbon\Carbon::parse($override->end_time)->format('H:i') }}
                                    @else
                                        <span class="text-muted">Sesuai default</span>
                                    @endif
                                </td>
                                <td>
                                    @if($override->is_closed)
                                        <span class="text-muted">-</span>
                                    @elseif($override->max_slots)
                                        <span class="badge bg-info">{{ $override->max_slots }} pasien</span>
                                    @else
                                        <span class="text-muted">Sesuai default</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ Str::limit($override->reason, 50) }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('schedules.overrides.edit', $override) }}"
                                           class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger"
                                                onclick="confirmDelete{{ $override->id }}()">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>

                                    <form id="delete-form-{{ $override->id }}"
                                          action="{{ route('schedules.overrides.destroy', $override) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <script>
                                        function confirmDelete{{ $override->id }}() {
                                            Swal.fire({
                                                title: 'Hapus Override?',
                                                text: 'Override untuk tanggal {{ Carbon\Carbon::parse($override->date)->format('d M Y') }} akan dihapus.',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('delete-form-{{ $override->id }}').submit();
                                                }
                                            });
                                        }
                                    </script>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $overrides->links() }}
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Belum ada override jadwal.
                    <a href="{{ route('schedules.overrides.create') }}">Tambah override pertama</a>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
