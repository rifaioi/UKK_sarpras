<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Data User<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Data User</h1>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <a href="<?= base_url('admin/users/trash') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-trash"></i> Recycle Bin
        </a>
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-tambah btn-sm-tambah">
            <i class="bi bi-person-plus-fill me-1"></i> Tambah User
        </a>
    </div>
</div>



<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Username</th>
                <th>Nama Lengkap</th>
                <th>Role</th>
                <th>Terdaftar</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $i => $user): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($user['username']) ?></td>
                <td><?= esc($user['nama_lengkap']) ?></td>
                <td>
                    <?php 
                        $displayRole = $user['nama_role'] == 'Member' ? 'Peminjam' : $user['nama_role'];
                        $role_badge = $user['nama_role'] == 'Admin' ? 'bg-primary' : ($user['nama_role'] == 'Petugas' ? 'bg-warning text-dark' : 'bg-success');
                    ?>
                    <span class="badge <?= $role_badge ?>"><?= esc($displayRole) ?></span>
                </td>
                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="<?= base_url('admin/users/edit/'.$user['id']) ?>" class="btn btn-action text-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('admin/users/delete/'.$user['id']) ?>" class="btn btn-action text-danger btn-delete" title="Delete">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
