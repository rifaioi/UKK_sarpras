<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?><?= isset($user) ? 'Edit User' : 'Tambah User' ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($user) ? 'Edit User' : 'Tambah User' ?></h1>
</div>

<div class="row">
    <div class="col-md-6">
        <?php if(session()->getFlashdata('errors')): ?>
            <div class="alert alert-danger">
                <ul>
                <?php foreach(session()->getFlashdata('errors') as $error): ?>
                    <li><?= $error ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="<?= isset($user) ? base_url('admin/users/update/'.$user['id']) : base_url('admin/users/store') ?>" method="post">
            
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
            <div class="mb-3">
                <label class="form-label">Password <?= isset($user) ? '(Kosongkan jika tidak ubah)' : '' ?></label>
                <input type="password" class="form-control" name="password"
                       minlength="8" <?= isset($user) ? '' : 'required' ?>>
                <small class="text-muted">Minimal 8 karakter</small>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('admin/users') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
