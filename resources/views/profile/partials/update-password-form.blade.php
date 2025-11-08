<form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
    @csrf
    @method('put')

    <div class="alert alert-info">
        <i class="bi bi-info-circle"></i>
        Pastikan menggunakan password yang panjang dan acak untuk keamanan akun Anda.
    </div>

    <div class="mb-3">
        <label for="update_password_current_password" class="form-label">
            Password Saat Ini <span class="text-danger">*</span>
        </label>
        <input type="password"
               class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
               id="update_password_current_password"
               name="current_password"
               autocomplete="current-password"
               required>
        @error('current_password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="mb-3">
        <label for="update_password_password" class="form-label">
            Password Baru <span class="text-danger">*</span>
        </label>
        <input type="password"
               class="form-control @error('password', 'updatePassword') is-invalid @enderror"
               id="update_password_password"
               name="password"
               autocomplete="new-password"
               required>
        @error('password', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
        <small class="text-muted">Minimal 8 karakter</small>
    </div>

    <div class="mb-3">
        <label for="update_password_password_confirmation" class="form-label">
            Konfirmasi Password Baru <span class="text-danger">*</span>
        </label>
        <input type="password"
               class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
               id="update_password_password_confirmation"
               name="password_confirmation"
               autocomplete="new-password"
               required>
        @error('password_confirmation', 'updatePassword')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="d-flex align-items-center gap-3">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-shield-check"></i> Update Password
        </button>

        @if (session('status') === 'password-updated')
            <span class="text-success" id="password-status-message">
                <i class="bi bi-check-circle"></i> Password berhasil diupdate!
            </span>

            <script>
                setTimeout(() => {
                    document.getElementById('password-status-message').style.display = 'none';
                }, 3000);
            </script>
        @endif
    </div>
</form>
