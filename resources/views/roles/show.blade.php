@extends('layouts.app')

@section('title', 'Detail Role')
@section('page-title', 'Detail Role: ' . ucfirst($role->name))

@section('content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4>Role Information</h4>
                    <div class="btn-group">
                        <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning">
                            <i class="bi bi-pencil"></i> Edit Permissions
                        </a>
                        <a href="{{ route('roles.index') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <td width="200"><strong>Role Name</strong></td>
                            <td>
                                <span class="badge
                                    @if($role->name === 'owner') bg-danger
                                    @elseif($role->name === 'admin') bg-primary
                                    @else bg-success
                                    @endif fs-6">
                                    {{ ucfirst($role->name) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Users</strong></td>
                            <td>{{ $role->users_count }} user(s)</td>
                        </tr>
                        <tr>
                            <td><strong>Jumlah Permissions</strong></td>
                            <td>{{ $role->permissions->count() }} permission(s)</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5>Permissions ({{ $role->permissions->count() }})</h5>
            </div>
            <div class="card-body">
                @if($permissions->isNotEmpty())
                    @foreach($permissions as $category => $perms)
                        <div class="mb-4">
                            <h6 class="text-uppercase fw-bold border-bottom pb-2">
                                <i class="bi bi-folder"></i> {{ ucfirst($category) }}
                                <span class="badge bg-secondary">{{ $perms->count() }}</span>
                            </h6>
                            <div class="d-flex flex-wrap gap-2 mt-2">
                                @foreach($perms as $permission)
                                    <span class="badge bg-light text-dark border">
                                        <i class="bi bi-check-circle text-success"></i> {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Role ini belum memiliki permissions.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endsection
