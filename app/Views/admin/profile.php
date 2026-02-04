<?= $this->extend('admin/layout'); ?>
<?= $this->Section('page_title'); ?>Profile<?= $this->endSection(); ?>
<?= $this->section('content'); ?>
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Profil Admin</h3>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/profile/update') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?= session()->get('nama'); ?>" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" value="<?= session()->get('username'); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Profil</button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ubah Password</h3>
            </div>
            <div class="card-body">


                <form action="<?= base_url('admin/profile/update_password') ?>" method="post">
                    <?= csrf_field() ?>
                    <div class="form-group mb-3">
                        <label>Password Lama</label>
                        <input type="password" name="password_lama" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password Baru</label>
                        <input type="password" name="password_baru" class="form-control" required>
                    </div>
                    <div class="form-group mb-3">
                        <label>Konfirmasi Password Baru</label>
                        <input type="password" name="konfirmasi_password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan Password Baru</button>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>
