<?= $this->extend('admin/layout'); ?>
<?= $this->Section('page_title'); ?>Profile<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Profil Admin</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/profile/update') ?>" method="post" class="form-confirm">
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
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Ubah Password</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/profile/update_password') ?>" method="post" id="passwordForm" class="form-confirm">
                    <?= csrf_field() ?>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password Lama</label>
                        <input type="password" name="password_lama" class="form-control pe-5" id="pw_lama" required>
                        <button type="button" class="btn border-0 position-absolute bottom-0 end-0 me-1 mb-1 p-2 text-secondary toggle-pw" data-target="pw_lama">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Password Baru</label>
                        <input type="password" name="password_baru" class="form-control pe-5" id="pw_baru" required>
                        <button type="button" class="btn border-0 position-absolute bottom-0 end-0 me-1 mb-1 p-2 text-secondary toggle-pw" data-target="pw_baru">
                            <i class="bi bi-eye"></i>
                        </button>
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="mb-3 position-relative">
                        <label class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control pe-5" id="pw_konf" required>
                        <button type="button" class="btn border-0 position-absolute bottom-0 end-0 me-1 mb-1 p-2 text-secondary toggle-pw" data-target="pw_konf">
                            <i class="bi bi-eye"></i>
                        </button>
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

<script>
    // Toggle password visibility
    document.querySelectorAll('.toggle-pw').forEach(btn => {
        btn.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
            input.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    });

    // Password form validation
    const passwordForm = document.getElementById('passwordForm');
    const passwordLama = document.getElementById('pw_lama');
    const passwordBaru = document.getElementById('pw_baru');
    const konfirmasiPassword = document.getElementById('pw_konf');

    // Clear validation on input
    [passwordLama, passwordBaru, konfirmasiPassword].forEach(input => {
        input.addEventListener('input', function() {
            this.classList.remove('is-invalid');
            const feedback = this.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = '';
        });
    });

    // Validate password form before submission
    passwordForm.addEventListener('submit', function(e) {
        let isValid = true;

        // Clear all previous errors
        [passwordLama, passwordBaru, konfirmasiPassword].forEach(input => {
            input.classList.remove('is-invalid');
            const feedback = input.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = '';
        });

        // Validate password lama
        if (passwordLama.value.trim() === '') {
            passwordLama.classList.add('is-invalid');
            const feedback = passwordLama.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = 'Password lama harus diisi.';
            isValid = false;
        }

        // Validate password baru
        if (passwordBaru.value.trim() === '') {
            passwordBaru.classList.add('is-invalid');
            const feedback = passwordBaru.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = 'Password baru harus diisi.';
            isValid = false;
        } else if (passwordBaru.value.length < 8) {
            passwordBaru.classList.add('is-invalid');
            const feedback = passwordBaru.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = 'Password baru minimal 8 karakter.';
            isValid = false;
        } else if (passwordBaru.value === passwordLama.value) {
            passwordBaru.classList.add('is-invalid');
            const feedback = passwordBaru.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = 'Password baru harus berbeda dari password lama.';
            isValid = false;
        }

        // Validate konfirmasi password
        if (konfirmasiPassword.value.trim() === '') {
            konfirmasiPassword.classList.add('is-invalid');
            const feedback = konfirmasiPassword.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = 'Konfirmasi password harus diisi.';
            isValid = false;
        } else if (konfirmasiPassword.value !== passwordBaru.value) {
            konfirmasiPassword.classList.add('is-invalid');
            const feedback = konfirmasiPassword.parentElement.querySelector('.invalid-feedback');
            if (feedback) feedback.textContent = 'Konfirmasi password tidak cocok dengan password baru.';
            isValid = false;
        }

        // If validation fails, prevent form submission
        if (!isValid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    }, true); // Use capture phase to run before SweetAlert confirmation
</script>
<?= $this->endSection(); ?>
