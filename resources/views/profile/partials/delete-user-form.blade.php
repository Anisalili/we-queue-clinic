<div class="alert alert-danger">
    <i class="bi bi-exclamation-triangle"></i>
    <strong>Peringatan!</strong> Setelah akun dihapus, semua data dan informasi akan dihapus secara permanen.
</div>

<button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#confirmUserDeletionModal">
    <i class="bi bi-trash"></i> Hapus Akun
</button>

<!-- Modal -->
<div class="modal fade" id="confirmUserDeletionModal" tabindex="-1" aria-labelledby="confirmUserDeletionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                        <i class="bi bi-exclamation-triangle"></i> Konfirmasi Hapus Akun
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <p class="mb-3">
                        Apakah Anda yakin ingin menghapus akun Anda? Setelah akun dihapus, semua data dan informasi akan dihapus secara permanen.
                    </p>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Masukkan password untuk konfirmasi"
                               required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Ya, Hapus Akun Saya
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if ($errors->userDeletion->any())
<script>
    // Auto show modal if there are errors
    document.addEventListener('DOMContentLoaded', function() {
        var modal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
        modal.show();
    });
</script>
@endif
