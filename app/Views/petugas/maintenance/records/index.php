<?php 
$layout = (session()->get('role_id') == 1) ? 'admin/layout' : 'petugas/layout';
$role = (session()->get('role_id') == 1) ? 'admin' : 'petugas';
?>
<?= $this->extend($layout) ?>
<?= $this->section('page_title') ?>Riwayat Maintenance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Maintenance</h1>
    <div class="btn-toolbar">
        <a href="<?= base_url($role.'/maintenance/upcoming') ?>" class="btn btn-sm btn-warning me-2">
            <i class="bi bi-clock"></i> Upcoming Maintenance
        </a>
        <a href="<?= base_url($role.'/maintenance/records/create') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Catat Maintenance
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Kode</th>
                <th>Jadwal</th>
                <th>Teknisi</th>
                <th>Deskripsi</th>
                <th>Next Maintenance</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($records)): ?>
            <tr>
                <td colspan="8" class="text-center">Belum ada data maintenance</td>
            </tr>
            <?php else: ?>
                <?php foreach($records as $record): ?>
                <tr>
                    <td><?= date('d/m/Y', strtotime($record['maintenance_date'])) ?></td>
                    <td><?= esc($record['nama_barang']) ?></td>
                    <td><code><?= esc($record['kode']) ?></code></td>
                    <td><?= ($record['schedule_name'] ?? null) ? '<span class="badge bg-info">'.esc($record['schedule_name']).'</span>' : '-' ?></td>
                    <td><?= esc($record['performed_by']) ?></td>
                    <td><?= esc(substr($record['description'], 0, 40)).'...' ?></td>
                    <td>
                        <?php if($record['next_maintenance_date']): ?>
                            <?= date('d/m/Y', strtotime($record['next_maintenance_date'])) ?>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?= base_url($role.'/maintenance/records/history/'.$record['sarpras_id']) ?>" class="btn btn-sm btn-info" title="History">
                            <i class="bi bi-clock-history"></i>
                        </a>
                        <a href="<?= base_url($role.'/maintenance/records/timeline/'.$record['sarpras_id']) ?>" class="btn btn-sm btn-success" title="Timeline">
                            <i class="bi bi-diagram-3"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
