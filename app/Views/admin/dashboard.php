<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Dashboard Admin<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard </h1>
</div>

<!-- Statistik Cards -->
<div class="row g-3 g-lg-4 mb-4">
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 h-100 card-gradient-primary">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-primary p-2 p-lg-3 rounded-3 me-3">
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-primary small fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Total Barang</h6>
                        <h2 class="fw-bold mb-0 text-primary h3 h2-lg"><?= $total_sarpras ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 h-100 card-gradient-info">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-primary p-2 p-lg-3 rounded-3 me-3" style="filter: hue-rotate(180deg);">
                        <i class="bi bi-arrow-left-right fs-3 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-info small fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Pinjaman Aktif</h6>
                        <h2 class="fw-bold mb-0 text-info h3 h2-lg"><?= $active_peminjaman ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 h-100 card-gradient-danger">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-danger p-2 p-lg-3 rounded-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-danger small fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Perbaikan</h6>
                        <h2 class="fw-bold mb-0 text-danger h3 h2-lg"><?= $damaged_sarpras ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-md-3">
        <div class="card border-0 h-100 card-gradient-warning">
            <div class="card-body p-3 p-lg-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-warning p-2 p-lg-3 rounded-3 me-3">
                        <i class="bi bi-envelope-exclamation-fill fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-warning small fw-bold text-uppercase mb-1" style="font-size: 0.7rem;">Pengaduan</h6>
                        <h2 class="fw-bold mb-0 text-warning h3 h2-lg"><?= $pengaduan_masuk ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="row">
    <!-- Top Defects & Maintenance -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent py-3 d-flex justify-content-between align-items-center">
                <h6 class="fw-bold mb-0 text-danger"><i class="bi bi-exclamation-octagon me-2"></i> Top 5 Aset Sering Bermasalah</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-10">
                            <tr>
                                <th class="ps-4">Nama Barang</th>
                                <th class="text-center">Total Masalah/Perbaikan</th>
                                <th class="pe-4 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($top_defects)): ?>
                                <tr><td colspan="3" class="text-center py-5 text-muted">Data kerusakan belum tersedia.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($top_defects as $index => $item): ?>
                                <tr>
                                    <td class="ps-4">
                                        <div class="d-flex align-items-center">
                                            <span class="badge bg-danger rounded-circle me-3" style="width: 24px; height: 24px; display: flex; align-items: center; justify-content: center;"><?= $index + 1 ?></span>
                                            <span class="fw-medium text-dark"><?= esc($item['nama']) ?></span>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <h5 class="mb-0 fw-bold text-danger"><?= esc($item['total_problems']) ?>x</h5>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <div class="progress" style="height: 6px; width: 100px; display: inline-flex;">
                                            <div class="progress-bar bg-danger" role="progressbar" style="width: <?= min(100, ($item['total_problems'] * 10)) ?>%"></div>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Side Column -->
    <div class="col-lg-4 mb-4">
        <!-- Asset Health Widget -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-bold py-3 text-success">
                <i class="bi bi-heart-pulse me-2"></i> Kesehatan Aset
            </div>
            <div class="card-body text-center p-4">
                <div class="position-relative d-inline-block mb-3">
                    <svg class="progress-ring" width="120" height="120">
                        <circle class="progress-ring__circle-bg" stroke="#e9ecef" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"/>
                        <circle class="progress-ring__circle" stroke="<?= $asset_health >= 80 ? '#198754' : ($asset_health >= 50 ? '#ffc107' : '#dc3545') ?>" stroke-width="8" fill="transparent" r="52" cx="60" cy="60"
                                style="stroke-dasharray: 326.72; stroke-dashoffset: <?= 326.72 - (326.72 * $asset_health / 100) ?>; transition: stroke-dashoffset 1s ease-in-out; transform: rotate(-90deg); transform-origin: 50% 50%;"/>
                    </svg>
                    <div class="position-absolute top-50 start-50 translate-middle text-center">
                        <h2 class="mb-0 fw-bold <?= $asset_health >= 80 ? 'text-success' : ($asset_health >= 50 ? 'text-warning' : 'text-danger') ?>"><?= $asset_health ?>%</h2>
                        <small class="text-muted" style="font-size: 0.65rem;">KONDISI PRIMA</small>
                    </div>
                </div>
                <div class="row text-center mt-2">
                    <div class="col-6 border-end">
                        <h5 class="mb-0 fw-bold text-dark"><?= $good_sarpras ?></h5>
                        <small class="text-muted" style="font-size: 0.7rem;">Baik</small>
                    </div>
                    <div class="col-6">
                        <h5 class="mb-0 fw-bold text-dark"><?= $total_sarpras ?></h5>
                        <small class="text-muted" style="font-size: 0.7rem;">Total Aset</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Maintenance List -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent fw-bold py-3 text-info">
                <i class="bi bi-calendar-event me-2"></i> Maintenance Terdekat
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($upcoming_maintenance)): ?>
                        <div class="p-4 text-center text-muted small">
                            <i class="bi bi-calendar-x fs-1 d-block mb-2 opacity-25"></i>
                            Tidak ada jadwal maintenance.
                        </div>
                    <?php endif; ?>
                    <?php foreach ($upcoming_maintenance as $m): ?>
                        <div class="list-group-item py-3">
                            <div class="d-flex w-100 justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1 fw-bold text-white small"><?= esc($m['asset_name']) ?></h6>
                                    <small class="text-secondary d-block" style="font-size: 0.75rem;"><?= esc($m['maintenance_type']) ?> &bull; <?= esc($m['technician']) ?></small>
                                </div>
                                <div class="text-end">
                                    <div class="badge bg-info text-dark mb-1"><?= date('d M Y', strtotime($m['scheduled_date'])) ?></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="card-footer bg-transparent border-0 text-center p-3">
                    <a href="<?= base_url('admin/maintenance/schedules') ?>" class="btn btn-sm btn-action w-100">Kelola Jadwal</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity (Landscape) -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center py-3">
                <h6 class="fw-bold mb-0 text-white"><i class="bi bi-clock-history text-primary me-2"></i> Aktivitas Terkini (Log Sistem)</h6>
                <a href="<?= base_url('admin/log') ?>" class="btn btn-sm btn-outline-primary px-3 rounded-pill">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light bg-opacity-10">
                            <tr>
                                <th class="ps-4" style="width: 15%;">Waktu</th>
                                <th style="width: 20%;">User</th>
                                <th style="width: 15%;">Aksi</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($recent_activities)): ?>
                                <tr><td colspan="4" class="text-center py-5 text-muted">Belum ada aktivitas tercatat.</td></tr>
                            <?php endif; ?>
                            <?php foreach ($recent_activities as $activity): ?>
                                <tr>
                                    <td class="ps-4 text-muted small"><?= date('d M Y, H:i', strtotime($activity['created_at'])) ?></td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($activity['nama_lengkap']) ?>&size=24&background=random" class="rounded-circle me-2" width="24" height="24">
                                            <span class="small fw-medium"><?= esc($activity['nama_lengkap']) ?></span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary bg-opacity-25 text-light border border-secondary"><?= esc($activity['aksi']) ?></span></td>
                                    <td class="small text-secondary"><?= esc($activity['deskripsi']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
