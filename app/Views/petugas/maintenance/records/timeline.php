<?php 
$layout = (session()->get('role_id') == 1) ? 'admin/layout' : 'petugas/layout';
$role = (session()->get('role_id') == 1) ? 'admin' : 'petugas';
?>
<?= $this->extend($layout) ?>
<?= $this->section('page_title') ?>Timeline<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Timeline - <?= esc($sarpras['nama']) ?></h1>
    <a href="<?= base_url($role.'/maintenance/records/history/'.$sarpras['id']) ?>" class="btn btn-sm btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke History
    </a>
</div>

<div class="card mb-3">
    <div class="card-body">
        <h5>Informasi Barang</h5>
        <p><strong>Kode:</strong> <?= esc($sarpras['kode']) ?></p>
        <p><strong>Nama:</strong> <?= esc($sarpras['nama']) ?></p>
    </div>
</div>

<h5 class="mb-3">Timeline (Maintenance + Pengembalian)</h5>

<?php if(empty($timeline)): ?>
    <div class="alert alert-info">Belum ada event untuk barang ini</div>
<?php else: ?>
    <div class="timeline">
        <?php foreach($timeline as $event): ?>
            <?php if($event['type'] == 'maintenance'): ?>
                <div class="card mb-3 border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="card-title">
                                <i class="bi bi-tools text-primary"></i> Maintenance
                            </h6>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($event['date'])) ?></small>
                        </div>
                        <p class="mb-1"><strong>Teknisi:</strong> <?= esc($event['data']['performed_by']) ?></p>
                        <p class="mb-1"><strong>Deskripsi:</strong> <?= esc($event['data']['description']) ?></p>
                        <?php if($event['data']['schedule_name'] ?? null): ?>
                            <p class="mb-0"><span class="badge bg-info"><?= esc($event['data']['schedule_name']) ?></span></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="card mb-3 border-warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h6 class="card-title">
                                <i class="bi bi-box-arrow-in-down text-warning"></i> Pengembalian
                            </h6>
                            <small class="text-muted"><?= date('d/m/Y', strtotime($event['date'])) ?></small>
                        </div>
                        <p class="mb-1"><strong>Peminjam:</strong> <?= esc($event['data']['peminjam']) ?></p>
                        <p class="mb-1"><strong>Kondisi:</strong> 
                            <?php 
                            $kondisi = $event['data']['nama_kondisi'];
                            $badgeClass = 'bg-success';
                            if($kondisi != 'Baik') $badgeClass = 'bg-danger';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= esc($kondisi) ?></span>
                        </p>
                        <?php if($event['data']['deskripsi']): ?>
                            <p class="mb-0"><strong>Keterangan:</strong> <?= esc($event['data']['deskripsi']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
