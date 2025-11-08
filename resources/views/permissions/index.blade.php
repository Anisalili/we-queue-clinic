@extends('layouts.app')

@section('title', 'Permission List')
@section('page-title', 'Permission List')

@section('content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Daftar Permissions</h4>
                    <span class="badge bg-primary fs-6">Total: {{ $totalPermissions }} permissions</span>
                </div>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle"></i>
                    <strong>Read Only:</strong> Halaman ini hanya menampilkan daftar permissions yang tersedia di sistem.
                    Untuk mengubah permissions, silakan edit melalui <a href="{{ route('roles.index') }}" class="alert-link">Role Management</a>.
                </div>

                @foreach($permissions as $category => $perms)
                    <div class="card mb-3">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 text-uppercase fw-bold">
                                <i class="bi bi-folder"></i> {{ ucfirst($category) }} Permissions
                                <span class="badge bg-secondary">{{ $perms->count() }}</span>
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th width="50">#</th>
                                            <th>Permission Name</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($perms as $index => $permission)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <code class="text-primary">{{ $permission->name }}</code>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        @php
                                                            $descriptions = [
                                                                'view.dashboard.v1' => 'Akses dashboard pasien',
                                                                'view.dashboard.v2' => 'Akses dashboard admin',
                                                                'view.dashboard.v3' => 'Akses dashboard owner',
                                                                'booking.create' => 'Membuat booking baru',
                                                                'booking.view.own' => 'Melihat booking sendiri',
                                                                'booking.view.all' => 'Melihat semua booking',
                                                                'booking.cancel.own' => 'Membatalkan booking sendiri',
                                                                'booking.cancel.any' => 'Membatalkan booking siapa saja',
                                                                'booking.update' => 'Mengubah booking',
                                                                'queue.view' => 'Melihat antrian',
                                                                'queue.manage' => 'Mengelola antrian',
                                                                'queue.call' => 'Memanggil pasien',
                                                                'patient.register' => 'Mendaftarkan pasien walk-in',
                                                                'patient.view.own' => 'Melihat data diri sendiri',
                                                                'patient.view.all' => 'Melihat data semua pasien',
                                                                'patient.update.own' => 'Mengubah data diri sendiri',
                                                                'patient.update.any' => 'Mengubah data pasien lain',
                                                                'schedule.view' => 'Melihat jadwal',
                                                                'schedule.configure' => 'Mengkonfigurasi jadwal',
                                                                'schedule.override' => 'Override jadwal',
                                                                'report.view' => 'Melihat laporan',
                                                                'report.export' => 'Export laporan',
                                                                'report.analytics' => 'Akses analytics',
                                                                'notification.send.manual' => 'Kirim notifikasi manual',
                                                                'notification.view.log' => 'Melihat log notifikasi',
                                                                'user.view' => 'Melihat daftar user',
                                                                'user.create' => 'Membuat user baru',
                                                                'user.update' => 'Mengubah user',
                                                                'user.delete' => 'Menghapus user',
                                                            ];
                                                        @endphp
                                                        {{ $descriptions[$permission->name] ?? 'Permission untuk ' . $permission->name }}
                                                    </small>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endsection
