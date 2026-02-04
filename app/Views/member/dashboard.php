<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Dashboard<?= $this->endSection(); ?>

<?= $this->section('content') ?>

<div class="row pt-4 mb-4">
    <div class="col-12">
        <h4 class="fw-bold mb-4">Dashboard Siswa</h4>
        
        <!-- Quick Actions Area -->
        <div class="row g-3 mb-5">
            <div class="col-md-4">
                <a href="<?= base_url('member/items') ?>" class="text-decoration-none h-100 d-block">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4 text-center">
                            <div class="bg-accent-primary p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-plus-circle-fill text-accent-primary fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-white mb-1">Pinjam Barang</h6>
                            <p class="small text-secondary mb-0">Klik sini untuk pinjam</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?= base_url('member/pengaduan') ?>" class="text-decoration-none h-100 d-block">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4 text-center">
                            <div class="bg-accent-danger p-3 rounded-circle d-inline-block mb-3">
                                <i class="bi bi-exclamation-square-fill text-accent-danger fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-white mb-1">Lapor Rusak</h6>
                            <p class="small text-secondary mb-0">Lapor barang yang rusak</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-4">
                <a href="<?= base_url('member/history') ?>" class="text-decoration-none h-100 d-block">
                    <div class="card h-100 border-0">
                        <div class="card-body p-4 text-center">
                            <div class="bg-accent-primary p-3 rounded-circle d-inline-block mb-3" style="filter: hue-rotate(240deg);">
                                <i class="bi bi-clock-fill text-info fs-2"></i>
                            </div>
                            <h6 class="fw-bold text-white mb-1">Riwayat Saya</h6>
                            <p class="small text-secondary mb-0">Cek barang yang dipinjam</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Recent Status -->
        <div class="row g-4 mb-5">
             <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body py-3 px-4 d-flex align-items-center">
                        <div class="me-3 text-accent-primary"><i class="bi bi-box-seam fs-4"></i></div>
                        <div>
                            <div class="small opacity-50">Sedang Dipinjam</div>
                            <div class="fw-bold text-accent-primary"><?= $active_loans_count ?> Barang</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0">
                    <div class="card-body py-3 px-4 d-flex align-items-center">
                        <div class="me-3 text-accent-warning"><i class="bi bi-hourglass-split fs-4"></i></div>
                        <div>
                            <div class="small opacity-50">Menunggu Approval</div>
                            <div class="fw-bold text-accent-warning"><?= $pending_loans_count ?> Permintaan</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Timeline Section -->
        <div class="card border-0">
            <div class="card-header bg-transparent py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-activity text-accent-primary me-2"></i> Aktivitas Terakhir</h6>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recent_activities)): ?>
                        <div class="p-5 text-center text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2 opacity-25"></i>
                            Belum ada aktivitas baru.
                        </div>
                    <?php endif; ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="list-group-item py-3 px-4">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold small text-white"><?= esc($activity['aksi']) ?></h6>
                                <small class="text-muted" style="font-size: 0.7rem;"><?= date('d M, H:i', strtotime($activity['created_at'])) ?></small>
                            </div>
                            <p class="mb-0 small text-secondary"><?= esc($activity['deskripsi']) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
