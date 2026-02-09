<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title') ?>Timeline - <?= esc($sarpras['nama']) ?><?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Timeline Barang: <?= esc($sarpras['nama']) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/maintenance/priority') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title fw-bold mb-3">Detail Barang</h5>
                <p class="mb-1 text-muted small uppercase fw-bold">Kode Inventaris</p>
                <p class="fw-bold mb-3"><code><?= $sarpras['kode'] ?></code></p>
                
                <p class="mb-1 text-muted small uppercase fw-bold">Terakhir Maintenance</p>
                <p class="mb-3"><?= $sarpras['last_maintenance_date'] ?: 'Belum pernah' ?></p>
                
                <p class="mb-1 text-muted small uppercase fw-bold">Jadwal Berikutnya</p>
                <p class="text-warning fw-bold mb-0"><?= $sarpras['next_maintenance_date'] ?: 'Tidak dijadwalkan' ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="timeline">
            <?php if(empty($timeline)): ?>
                <div class="alert alert-info border-0 shadow-sm">
                    Belum ada riwayat aktivitas untuk barang ini.
                </div>
            <?php endif; ?>

            <?php foreach($timeline as $item): ?>
                <div class="timeline-item mb-4 position-relative ps-5">
                    <!-- Dot Icon -->
                    <div class="timeline-dot position-absolute start-0 top-0 d-flex align-items-center justify-content-center rounded-circle shadow-sm" 
                         style="width: 40px; height: 40px; z-index: 2; background: <?= $item['type'] == 'maintenance' ? '#4e73df' : '#e74a3b' ?>;">
                        <i class="bi <?= $item['type'] == 'maintenance' ? 'bi-wrench text-white' : 'bi-exclamation-triangle text-white' ?>"></i>
                    </div>
                    
                    <!-- Line Connector -->
                    <div class="timeline-line position-absolute start-0 top-0 h-100 border-start border-2 opacity-25" style="margin-left: 19px; z-index: 1;"></div>
                    
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge <?= $item['type'] == 'maintenance' ? 'bg-primary' : 'bg-danger' ?> rounded-pill">
                                    <?= $item['type'] == 'maintenance' ? 'MAINTENANCE' : 'BREAKDOWN/RETURN' ?>
                                </span>
                                <small class="text-muted"><i class="bi bi-calendar3 me-1"></i><?= date('d M Y', strtotime($item['date'])) ?></small>
                            </div>
                            
                            <?php if($item['type'] == 'maintenance'): ?>
                                <h6 class="fw-bold mb-1"><?= esc($item['data']['description']) ?></h6>
                                <p class="text-muted small mb-2"><?= esc($item['data']['notes']) ?></p>
                                <div class="d-flex align-items-center gap-3">
                                    <small class="text-primary fw-medium"><i class="bi bi-person me-1"></i><?= esc($item['data']['performed_by']) ?></small>
                                    <?php if($item['data']['cost']): ?>
                                        <small class="text-success fw-medium"><i class="bi bi-cash me-1"></i>Rp <?= number_format($item['data']['cost'], 0, ',', '.') ?></small>
                                    <?php endif; ?>
                                </div>
                            <?php else: ?>
                                <h6 class="fw-bold mb-1">Pengembalian dengan Kondisi: <?= esc($item['data']['nama_kondisi']) ?></h6>
                                <p class="text-muted small mb-2"><?= esc($item['data']['deskripsi'] ?: 'Tidak ada deskripsi kerusakan.') ?></p>
                                <small class="text-info fw-medium"><i class="bi bi-person me-1"></i>Dipinjam oleh: <?= esc($item['data']['peminjam']) ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<style>
.timeline-item:last-child .timeline-line {
    display: none;
}
.small.uppercase {
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>

<?= $this->endSection() ?>
