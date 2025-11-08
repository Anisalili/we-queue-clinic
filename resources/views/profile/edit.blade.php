@extends('layouts.app')

@section('title', 'Profil Saya')
@section('page-title', 'Profil Saya')

@section('content')
<section class="row">
    <div class="col-12 col-lg-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-column align-items-center text-center">
                    <div class="position-relative mb-3">
                        <div class="avatar avatar-xl" style="width: 120px; height: 120px;">
                            @if($user->avatar)
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;" id="avatar-preview">
                            @else
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 100%; height: 100%;" id="avatar-placeholder">
                                    <span class="text-white fs-1 fw-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                </div>
                            @endif
                        </div>
                        <button type="button" class="btn btn-primary btn-sm rounded-circle position-absolute bottom-0 end-0" style="width: 35px; height: 35px; padding: 0;" onclick="document.getElementById('avatar-input').click()">
                            <i class="bi bi-camera"></i>
                        </button>
                    </div>

                    <h4 class="mb-1">{{ $user->name }}</h4>
                    <p class="text-muted mb-3">{{ $user->email }}</p>

                    @if($user->hasRole('patient'))
                        <span class="badge bg-primary">Pasien</span>
                    @elseif($user->hasRole('admin'))
                        <span class="badge bg-success">Admin</span>
                    @elseif($user->hasRole('owner'))
                        <span class="badge bg-danger">Owner</span>
                    @endif

                    @if($user->avatar)
                    <form action="{{ route('profile.avatar.delete') }}" method="POST" class="mt-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Hapus foto profil?')">
                            <i class="bi bi-trash"></i> Hapus Foto
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informasi</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block">No. Telepon</small>
                    <strong>{{ $user->phone ?? '-' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Tanggal Lahir</small>
                    <strong>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('d F Y') : '-' }}</strong>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Alamat</small>
                    <strong>{{ $user->address ?? '-' }}</strong>
                </div>
                <div>
                    <small class="text-muted d-block">Bergabung Sejak</small>
                    <strong>{{ $user->created_at->format('d F Y') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-8">
        <!-- Update Profile Information -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Update Informasi Profil</h4>
            </div>
            <div class="card-body">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Update Password</h4>
            </div>
            <div class="card-body">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account (Only for patients) -->
        @if($user->hasRole('patient'))
        <div class="card border-danger">
            <div class="card-header bg-danger bg-opacity-10">
                <h4 class="card-title text-danger mb-0">Hapus Akun</h4>
            </div>
            <div class="card-body">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
function previewAvatar(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('avatar-preview');
            const placeholder = document.getElementById('avatar-placeholder');

            if (preview) {
                // Update existing image
                preview.src = e.target.result;
            } else if (placeholder) {
                // Replace placeholder with image
                const avatarContainer = placeholder.parentElement;
                avatarContainer.innerHTML = '<img src="' + e.target.result + '" alt="Preview" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;" id="avatar-preview">';
            }

            // Auto submit form after selecting image
            Swal.fire({
                title: 'Upload Foto Profil?',
                text: 'Foto profil akan diupdate',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#435ebe',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Upload!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Find and submit the form
                    event.target.closest('form').submit();
                } else {
                    // Reset file input
                    event.target.value = '';
                    // Reload page to restore original avatar
                    location.reload();
                }
            });
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endpush
