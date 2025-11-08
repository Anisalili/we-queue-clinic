@extends('layouts.app')

@section('title', 'Role Management')
@section('page-title', 'Role Management')

@section('content')
<section class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4>Daftar Role</h4>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Role Name</th>
                                <th>Jumlah Users</th>
                                <th>Jumlah Permissions</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <span class="badge
                                            @if($role->name === 'owner') bg-danger
                                            @elseif($role->name === 'admin') bg-primary
                                            @else bg-success
                                            @endif fs-6">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    </td>
                                    <td>{{ $role->users_count }} user(s)</td>
                                    <td>{{ $role->permissions_count }} permission(s)</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('roles.show', $role) }}" class="btn btn-sm btn-info">
                                                <i class="bi bi-eye"></i> Lihat
                                            </a>
                                            <a href="{{ route('roles.edit', $role) }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-pencil"></i> Edit Permissions
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i>
            <strong>Note:</strong> Role tidak dapat dihapus atau ditambah. Anda hanya bisa mengubah permissions yang dimiliki setiap role.
        </div>
    </div>
</section>
@endsection
