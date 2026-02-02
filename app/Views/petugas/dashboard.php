<?= $this->extend('petugas/layout') ?>

<?= $this->section('content') ?>
<h2>Dashboard Petugas</h2>
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-accent-warning p-3 rounded-3 me-3">
                        <i class="bi bi-hourglass-split fs-3 text-accent-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Menunggu Persetujuan</h6>
                        <h2 class="fw-bold mb-0 text-accent-warning"><?= $pending_peminjaman ?></h2>
                    </div>
                </div>
                <a href="<?= base_url('petugas/peminjaman') ?>" class="btn btn-outline-warning btn-sm w-100 rounded-pill">Lihat Permintaan</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-accent-success p-3 rounded-3 me-3">
                        <i class="bi bi-box-seam-fill fs-3 text-accent-success"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Sedang Dipinjam</h6>
                        <h2 class="fw-bold mb-0 text-accent-success"><?= $active_peminjaman ?></h2>
                    </div>
                </div>
                <a href="<?= base_url('petugas/pengembalian') ?>" class="btn btn-outline-success btn-sm w-100 rounded-pill">Kelola Pengembalian</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 h-100">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-accent-danger p-3 rounded-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-accent-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-secondary small fw-bold text-uppercase mb-1">Pengaduan Aktif</h6>
                        <h2 class="fw-bold mb-0 text-accent-danger"><?= $active_pengaduan ?></h2>
                    </div>
                </div>
                 <a href="<?= base_url('petugas/pengaduan') ?>" class="btn btn-outline-danger btn-sm w-100 rounded-pill">Tinjau Laporan</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center fw-bold py-3">
                <span><i class="bi bi-activity text-accent-primary me-2"></i> Aktivitas Operasional</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recent_activities)): ?>
                        <div class="p-5 text-center text-muted">
                             <i class="bi bi-inbox fs-1 d-block mb-3 opacity-25"></i>
                             Belum ada aktivitas tercatat.
                        </div>
                    <?php endif; ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="list-group-item py-3 px-4">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold text-white"><?= esc($activity['aksi']) ?></h6>
                                <small class="text-muted small"><?= date('H:i', strtotime($activity['created_at'])) ?></small>
                            </div>
                            <p class="mb-2 small text-secondary"><?= esc($activity['deskripsi']) ?></p>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($activity['nama_lengkap']) ?>&size=20&background=random" class="rounded-circle me-2" width="20" height="20">
                                <small class="text-accent-primary fw-medium"><?= esc($activity['nama_lengkap']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
