@extends('layouts.app')

@section('title', 'Detail User')
@section('page-title', 'Detail User')

@section('content')
<section class="row">
    <div class="col-12 col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Detail User</h4>
                    <div class="btn-group">
                        @can('user.update')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                <i class="bi bi-pencil"></i> Edit
                            </a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td width="200"><strong>Nama Lengkap</strong></td>
                            <td>{{ $user->name }}</td>
                        </tr>
                        <tr>
                            <td><strong>Email</strong></td>
                            <td>{{ $user->email }}</td>
                        </tr>
                        <tr>
                            <td><strong>No. HP</strong></td>
                            <td>{{ $user->phone ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Alamat</strong></td>
                            <td>{{ $user->address ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal Lahir</strong></td>
                            <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d F Y') : '-' }}</td>
                        </tr>
                        <tr>
                            <td><strong>Role</strong></td>
                            <td>
                                @foreach($user->roles as $role)
                                    <span class="badge
                                        @if($role->name === 'owner') bg-danger
                                        @elseif($role->name === 'admin') bg-primary
                                        @else bg-success
                                        @endif">
                                        {{ ucfirst($role->name) }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Dibuat</strong></td>
                            <td>{{ $user->created_at->format('d F Y, H:i') }} ({{ $user->created_at->diffForHumans() }})</td>
                        </tr>
                        <tr>
                            <td><strong>Terakhir Update</strong></td>
                            <td>{{ $user->updated_at->format('d F Y, H:i') }} ({{ $user->updated_at->diffForHumans() }})</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Permissions (Read Only) -->
        <div class="card mt-3">
            <div class="card-header">
                <h5>Permissions (Read Only)</h5>
            </div>
            <div class="card-body">
                @if($user->roles->isNotEmpty())
                    @php
                        $permissions = $user->roles->first()->permissions->groupBy(function($permission) {
                            return explode('.', $permission->name)[0];
                        });
                    @endphp

                    @foreach($permissions as $group => $perms)
                        <div class="mb-3">
                            <h6 class="text-uppercase fw-bold">{{ ucfirst($group) }}</h6>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach($perms as $permission)
                                    <span class="badge bg-light text-dark border">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">User ini belum memiliki role.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
