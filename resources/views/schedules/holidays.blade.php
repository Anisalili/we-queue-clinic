@extends('layouts.app')

@section('title', 'Kelola Hari Libur')

@section('content')
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Kelola Hari Libur</h3>
                <p class="text-subtitle text-muted">Atur hari libur nasional dan cuti klinik</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('schedules.index') }}">Jadwal</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Hari Libur</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Daftar Hari Libur</h4>
                <a href="{{ route('schedules.holidays.create') }}" class="btn btn-success">
                    <i class="bi bi-plus-circle"></i> Tambah Hari Libur
                </a>
            </div>
            <div class="card-body">
                @if($holidays->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Hari</th>
                                <th>Nama Libur</th>
                                <th>Tipe</th>
                                <th>Keterangan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($holidays as $holiday)
                            <tr>
                                <td>
                                    <strong>{{ Carbon\Carbon::parse($holiday->date)->format('d M Y') }}</strong>
                                </td>
                                <td>{{ Carbon\Carbon::parse($holiday->date)->locale('id')->translatedFormat('l') }}</td>
                                <td><strong>{{ $holiday->name }}</strong></td>
                                <td>
                                    @if($holiday->type === 'national')
                                        <span class="badge bg-primary">
                                            <i class="bi bi-flag"></i> Nasional
                                        </span>
                                    @elseif($holiday->type === 'clinic_leave')
                                        <span class="badge bg-warning">
                                            <i class="bi bi-calendar-x"></i> Cuti Klinik
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-exclamation-triangle"></i> Emergency
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $holiday->description ?? '-' }}</small>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger"
                                            onclick="confirmDelete{{ $holiday->id }}()">
                                        <i class="bi bi-trash"></i>
                                    </button>

                                    <form id="delete-form-{{ $holiday->id }}"
                                          action="{{ route('schedules.holidays.destroy', $holiday) }}"
                                          method="POST" class="d-none">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                    <script>
                                        function confirmDelete{{ $holiday->id }}() {
                                            Swal.fire({
                                                title: 'Hapus Hari Libur?',
                                                html: '<strong>{{ $holiday->name }}</strong><br>{{ Carbon\Carbon::parse($holiday->date)->format('d M Y') }}',
                                                icon: 'warning',
                                                showCancelButton: true,
                                                confirmButtonColor: '#d33',
                                                cancelButtonColor: '#3085d6',
                                                confirmButtonText: 'Ya, Hapus!',
                                                cancelButtonText: 'Batal'
                                            }).then((result) => {
                                                if (result.isConfirmed) {
                                                    document.getElementById('delete-form-{{ $holiday->id }}').submit();
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
                    {{ $holidays->links() }}
                </div>
                @else
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i> Belum ada hari libur terdaftar.
                    <a href="{{ route('schedules.holidays.create') }}">Tambah hari libur pertama</a>
                </div>
                @endif
            </div>
        </div>
    </section>
</div>
@endsection
