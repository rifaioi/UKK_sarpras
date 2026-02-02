<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Log Aktivitas User<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Log Aktivitas User</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Role</th>
                <th>Aksi</th>
                <th>Deskripsi</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logs as $i => $log): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($log['nama_lengkap']) ?></td>
                <td><span class="badge bg-secondary"><?= esc($log['nama_role']) ?></span></td>
                <td><span class="badge bg-primary"><?= esc($log['aksi']) ?></span></td>
                <td><?= esc($log['deskripsi']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
