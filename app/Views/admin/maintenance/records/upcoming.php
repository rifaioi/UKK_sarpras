<?php 
$layout = (session()->get('role_id') == 1) ? 'admin/layout' : 'petugas/layout';
$role = (session()->get('role_id') == 1) ? 'admin' : 'petugas';
?>
<?= $this->extend($layout) ?>
<?= $this->section('page_title') ?>Upcoming Maintenance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Maintenance yang Akan Datang & Terlambat</h1>
</div>

<?php if(!empty($overdue)): ?>
<div class="alert alert-danger">
    <h5><i class="bi bi-exclamation-triangle"></i> Maintenance Terlambat (<?= count($overdue) ?>)</h5>
</div>

<div class="table-responsive mb-4">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>Barang</th>
                <th>Kode</th>
                <th>Interval</th>
                <th>Seharusnya</th>
                <th>Terlambat</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($overdue as $item): ?>
            <tr>
                <td><?= esc($item['nama']) ?></td>
                <td><code><?= esc($item['kode']) ?></code></td>
                <td><?= $item['maintenance_interval'] ?> bulan</td>
                <td><?= date('d/m/Y', strtotime($item['next_maintenance_date'])) ?></td>
                <td><span class="badge bg-danger"><?= $item['days_overdue'] ?> hari</span></td>
                <td>
                    <a href="<?= base_url($role.'/maintenance/records/create/'.$item['id']) ?>" class="btn btn-sm btn-danger">
                        <i class="bi bi-tools"></i> Maintenance Sekarang
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<h5 class="mb-3">Upcoming (14 Hari Ke Depan)</h5>

<?php if(empty($upcoming)): ?>
    <div class="alert alert-info">Tidak ada maintenance yang dijadwalkan dalam 14 hari ke depan</div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm text-white">
            <thead>
                <tr>
                    <th>Barang</th>
                    <th>Kode</th>
                    <th>Interval</th>
                    <th>Tanggal Maintenance</th>
                    <th>Sisa Hari</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($upcoming as $item): ?>
                <tr>
                    <td><?= esc($item['nama']) ?></td>
                    <td><code><?= esc($item['kode']) ?></code></td>
                    <td><?= $item['maintenance_interval'] ?> bulan</td>
                    <td><?= date('d/m/Y', strtotime($item['next_maintenance_date'])) ?></td>
                    <td>
                        <?php 
                        $days = $item['days_until_due'];
                        $badgeClass = 'bg-success';
                        if($days <= 3) $badgeClass = 'bg-danger';
                        elseif($days <= 7) $badgeClass = 'bg-warning';
                        ?>
                        <span class="badge <?= $badgeClass ?>"><?= $days ?> hari lagi</span>
                    </td>
                    <td>
                        <a href="<?= base_url($role.'/maintenance/records/history/'.$item['id']) ?>" class="btn btn-sm btn-info">History</a>
                        <a href="<?= base_url($role.'/maintenance/records/create/'.$item['id']) ?>" class="btn btn-sm btn-primary">Catat</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
