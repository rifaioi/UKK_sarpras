<?= $this->extend('member/layout'); ?>
<?= $this->Section('page_title'); ?>Profil Saya<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Profil Saya</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('member/profile/update') ?>" method="post" class="form-confirm">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= $user['nama_lengkap']; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" value="<?= $user['username']; ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 mt-3">
                        <i class="bi bi-save me-2"></i>Update Profil
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('member/profile/update_password') ?>" method="post" id="passwordForm" class="form-confirm">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama" id="password_lama" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password_baru" id="password_baru" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" id="konfirmasi_password" class="form-control" required>
                        <div class="invalid-feedback"></div>
                    </div>
                    <button type="submit" class="btn btn-primary px-4 mt-3">
                        <i class="bi bi-key me-2"></i>Simpan Password Baru
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordForm = document.getElementById('passwordForm');
    const passwordLama = document.getElementById('password_lama');
    const passwordBaru = document.getElementById('password_baru');
    const konfirmasiPassword = document.getElementById('konfirmasi_password');

    // Clear validation on input
    [passwordLama, passwordBaru, konfirmasiPassword].forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            this.nextElementSibling.textContent = '';
        });
    });

    // Validate password form before submission
    passwordForm.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear all previous errors
        [passwordLama, passwordBaru, konfirmasiPassword].forEach(input => {
            input.classList.remove('is-invalid');
            input.nextElementSibling.textContent = '';
        });

        // Validate password lama
        if (passwordLama.value.trim() === '') {
            passwordLama.classList.add('is-invalid');
            passwordLama.nextElementSibling.textContent = 'Password lama harus diisi.';
            isValid = false;
        }

        // Validate password baru
        if (passwordBaru.value.trim() === '') {
            passwordBaru.classList.add('is-invalid');
            passwordBaru.nextElementSibling.textContent = 'Password baru harus diisi.';
            isValid = false;
        } else if (passwordBaru.value.length < 8) {
            passwordBaru.classList.add('is-invalid');
            passwordBaru.nextElementSibling.textContent = 'Password baru minimal 8 karakter.';
            isValid = false;
        } else if (passwordBaru.value === passwordLama.value) {
            passwordBaru.classList.add('is-invalid');
            passwordBaru.nextElementSibling.textContent = 'Password baru harus berbeda dari password lama.';
            isValid = false;
        }

        // Validate konfirmasi password
        if (konfirmasiPassword.value.trim() === '') {
            konfirmasiPassword.classList.add('is-invalid');
            konfirmasiPassword.nextElementSibling.textContent = 'Konfirmasi password harus diisi.';
            isValid = false;
        } else if (konfirmasiPassword.value !== passwordBaru.value) {
            konfirmasiPassword.classList.add('is-invalid');
            konfirmasiPassword.nextElementSibling.textContent = 'Konfirmasi password tidak cocok dengan password baru.';
            isValid = false;
        }

        // If validation fails, prevent form submission
        if (!isValid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    }, true); // Use capture phase to run before SweetAlert confirmation
});
</script>
<?= $this->endSection(); ?>
