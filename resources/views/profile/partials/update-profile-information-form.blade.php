<form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
    @csrf
    @method('patch')

    <!-- Hidden Avatar Input -->
    <input type="file" id="avatar-input" name="avatar" accept="image/*" class="d-none" onchange="previewAvatar(event)">
    @error('avatar')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="name" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text"
                   class="form-control @error('name') is-invalid @enderror"
                   id="name"
                   name="name"
                   value="{{ old('name', $user->name) }}"
                   required
                   autofocus>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email"
                   class="form-control @error('email') is-invalid @enderror"
                   id="email"
                   name="email"
                   value="{{ old('email', $user->email) }}"
                   required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="alert alert-warning mt-2">
                    <p class="mb-2">Email Anda belum diverifikasi.</p>
                    <form method="post" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="phone" class="form-label">No. Telepon</label>
            <input type="text"
                   class="form-control @error('phone') is-invalid @enderror"
                   id="phone"
                   name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   placeholder="08xxxxxxxxxx">
            @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 mb-3">
            <label for="date_of_birth" class="form-label">Tanggal Lahir</label>
            <input type="date"
                   class="form-control @error('date_of_birth') is-invalid @enderror"
                   id="date_of_birth"
                   name="date_of_birth"
                   value="{{ old('date_of_birth', $user->date_of_birth) }}">
            @error('date_of_birth')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Alamat</label>
        <textarea class="form-control @error('address') is-invalid @enderror"
                  id="address"
                  name="address"
                  rows="3"
                  placeholder="Alamat lengkap">{{ old('address', $user->address) }}</textarea>
        @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Simpan Perubahan
        </button>

        @if (session('status') === 'profile-updated')
            <span class="text-success" id="status-message">
                <i class="bi bi-check-circle"></i> Profil berhasil diupdate!
            </span>

            <script>
                setTimeout(() => {
                    document.getElementById('status-message').style.display = 'none';
                }, 3000);
            </script>
        @endif
    </div>
</form>
