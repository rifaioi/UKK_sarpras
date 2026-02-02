<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Manajemen User<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen User</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/users/create') ?>" class="btn btn-sm btn-outline-primary">
            <i class="bi bi-person-plus-fill"></i> Tambah User
        </a>
    </div>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

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
                        $role_badge = $user['nama_role'] == 'Admin' ? 'bg-primary' : ($user['nama_role'] == 'Petugas' ? 'bg-warning text-dark' : 'bg-success');
                    ?>
                    <span class="badge <?= $role_badge ?>"><?= esc($user['nama_role']) ?></span>
                </td>
                <td><?= date('d/m/Y', strtotime($user['created_at'])) ?></td>
                <td>
                    <a href="<?= base_url('admin/users/edit/'.$user['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('admin/users/delete/'.$user['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Apakah anda yakin?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
