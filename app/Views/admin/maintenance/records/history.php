<?php 
$layout = (session()->get('role_id') == 1) ? 'admin/layout' : 'petugas/layout';
$role = (session()->get('role_id') == 1) ? 'admin' : 'petugas';
?>
<?= $this->extend($layout) ?>
<?= $this->section('page_title') ?>History Maintenance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">History Maintenance - <?= esc($sarpras['nama']) ?></h1>
    <div class="btn-toolbar">
        <a href="<?= base_url($role.'/maintenance/records/timeline/'.$sarpras['id']) ?>" class="btn btn-sm btn-success me-2">
            <i class="bi bi-diagram-3"></i> Lihat Timeline
        </a>
        <a href="<?= base_url($role.'/maintenance/records/create/'.$sarpras['id']) ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Catat Maintenance
        </a>
    </div>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5>Informasi Barang</h5>
        <p><strong>Kode:</strong> <?= esc($sarpras['kode']) ?></p>
        <p><strong>Nama:</strong> <?= esc($sarpras['nama']) ?></p>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Teknisi</th>
                <th>Deskripsi</th>
                <th>Biaya</th>
                <th>Next Maintenance</th>
                <th>Dicatat Oleh</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($records)): ?>
            <tr>
                <td colspan="7" class="text-center">Belum ada history maintenance untuk barang ini</td>
            </tr>
            <?php else: ?>
                <?php foreach($records as $record): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($record['maintenance_date'])) ?></td>
                    <td><?= esc($record['performed_by']) ?></td>
                    <td>
                        <?= esc($record['description']) ?>
                        <?php if($record['notes']): ?>
                            <br><small class="text-muted"><?= esc($record['notes']) ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?= $record['cost'] ? 'Rp '.number_format($record['cost'], 0, ',', '.') : '-' ?></td>
                    <td><?= $record['next_maintenance_date'] ? date('d/m/Y', strtotime($record['next_maintenance_date'])) : '-' ?></td>
                    <td><?= esc($record['creator_name']) ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<a href="<?= base_url($role.'/maintenance/records') ?>" class="btn btn-secondary">Kembali</a>

<?= $this->endSection() ?>
