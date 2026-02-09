<?= $this->extend('petugas/layout') ?>

<?= $this->section('content') ?>
<h2>Dashboard Petugas</h2>
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-4">
        <div class="card border-0 h-100 card-gradient-warning">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-accent-warning p-3 rounded-3 me-3">
                        <i class="bi bi-hourglass-split fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-warning small fw-bold text-uppercase mb-1">Menunggu Approval</h6>
                        <h2 class="fw-bold mb-0 text-warning"><?= $pending_peminjaman ?></h2>
                    </div>
                </div>
                <a href="<?= base_url('petugas/peminjaman') ?>" class="btn btn-warning text-dark btn-sm w-100 rounded-pill fw-bold">Lihat Permintaan</a>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-4">
        <div class="card border-0 h-100 card-gradient-success">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-accent-success p-3 rounded-3 me-3">
                        <i class="bi bi-box-seam-fill fs-3 text-success"></i>
                    </div>
                    <div>
                        <h6 class="text-success small fw-bold text-uppercase mb-1">Sedang Dipinjam</h6>
                        <h2 class="fw-bold mb-0 text-success"><?= $active_peminjaman ?></h2>
                    </div>
                </div>
                <a href="<?= base_url('petugas/pengembalian') ?>" class="btn btn-success text-white btn-sm w-100 rounded-pill fw-bold">Kelola Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-12">
        <div class="card border-0 h-100 card-gradient-danger">
            <div class="card-body p-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-accent-danger p-3 rounded-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-danger small fw-bold text-uppercase mb-1">Pengaduan Aktif</h6>
                        <h2 class="fw-bold mb-0 text-danger"><?= $active_pengaduan ?></h2>
                    </div>
                </div>
                 <a href="<?= base_url('petugas/pengaduan') ?>" class="btn btn-danger text-white btn-sm w-100 rounded-pill fw-bold">Tinjau Laporan</a>
            </div>
        </div>
    </div>
</div>

<!-- Maintenance Reminders -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold py-3">
                <i class="bi bi-calendar-check text-info me-2"></i> Jadwal Maintenance Mendatang
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($upcoming_maintenance)): ?>
                        <div class="p-4 text-center text-muted small">Tidak ada jadwal maintenance terdekat.</div>
                    <?php endif; ?>
                    <?php foreach ($upcoming_maintenance as $m): ?>
                        <div class="list-group-item py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold text-white"><?= esc($m['asset_name']) ?></h6>
                                    <small class="text-secondary small"><?= esc($m['maintenance_type']) ?> - <?= esc($m['technician']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-info text-dark mb-1"><?= date('d M Y', strtotime($m['scheduled_date'])) ?></div>
                                    <a href="<?= base_url('petugas/maintenance/records') ?>" class="btn btn-sm btn-outline-info d-block">Detail</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
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
