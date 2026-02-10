<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?><?= isset($user) ? 'Edit User' : 'Tambah User' ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($user) ? 'Edit User' : 'Tambah User' ?></h1>
</div>

<div class="row">
    <div class="col-md-6">


        <form action="<?= isset($user) ? base_url('admin/users/update/'.$user['id']) : base_url('admin/users/store') ?>" method="post" class="form-confirm">
            
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username"
                       value="<?= isset($user) ? $user['username'] : old('username') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" name="nama_lengkap"
                       value="<?= isset($user) ? $user['nama_lengkap'] : old('nama_lengkap') ?>" required>
            </div>

            <!-- ROLE RADIO BUTTON -->
            <div class="mb-3">
                <label class="form-label">Role</label>
                <div>
                    <?php foreach($roles as $role): ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="role_id" id="role_<?= $role['id'] ?>" value="<?= $role['id'] ?>" <?= (isset($user) && $user['role_id'] == $role['id']) ? 'checked' : '' ?> required>
                            <label class="form-check-label" for="role_<?= $role['id'] ?>"><?= $role['nama_role'] ?></label>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- PASSWORD -->
            <div class="mb-3 position-relative">
                <label class="form-label">Password <?= isset($user) ? '(Kosongkan jika tidak ubah)' : '' ?></label>
                <div class="position-relative">
                    <input type="password" class="form-control pe-5" name="password" id="user_password"
                           minlength="8" <?= isset($user) ? '' : 'required' ?>>
                    <button type="button" class="btn border-0 position-absolute top-50 end-0 translate-middle-y me-1 p-2 text-secondary" id="toggleUserPw">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <small class="text-muted">Minimal 8 karakter</small>
            </div>
            
            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i>Simpan
                </button>
                <a href="<?= base_url('admin/users') ?>" class="btn btn-action px-4 d-flex align-items-center">
                    <i class="bi bi-x-circle me-2"></i>Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    const toggleUserPw = document.querySelector('#toggleUserPw');
    const userPassword = document.querySelector('#user_password');

    if (toggleUserPw) {
        toggleUserPw.addEventListener('click', function() {
            const type = userPassword.getAttribute('type') === 'password' ? 'text' : 'password';
            userPassword.setAttribute('type', type);
            this.querySelector('i').classList.toggle('bi-eye');
            this.querySelector('i').classList.toggle('bi-eye-slash');
        });
    }
</script>
<?= $this->endSection() ?>
