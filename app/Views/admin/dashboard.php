<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Dashboard Admin<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard </h1>
</div>

<!-- Statistik Cards -->
<div class="row g-5 mb-5">
    <div class="col-md-3">
        <div class="card border-0 h-100 card-gradient-primary">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-primary p-3 rounded-3 me-3">
                        <i class="bi bi-box-seam fs-3 text-primary"></i>
                    </div>
                    <div>
                        <h6 class="text-primary small fw-bold text-uppercase mb-1">Total Barang</h6>
                        <h2 class="fw-bold mb-0 text-primary"><?= $total_sarpras ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100 card-gradient-info">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-primary p-3 rounded-3 me-3" style="filter: hue-rotate(180deg);">
                        <i class="bi bi-arrow-left-right fs-3 text-info"></i>
                    </div>
                    <div>
                        <h6 class="text-info small fw-bold text-uppercase mb-1">Pinjaman Aktif</h6>
                        <h2 class="fw-bold mb-0 text-info"><?= $active_peminjaman ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100 card-gradient-danger">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-danger p-3 rounded-3 me-3">
                        <i class="bi bi-exclamation-triangle-fill fs-3 text-danger"></i>
                    </div>
                    <div>
                        <h6 class="text-danger small fw-bold text-uppercase mb-1">Sedang Dalam Perbaikan</h6>
                        <h2 class="fw-bold mb-0 text-danger"><?= $damaged_sarpras ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 h-100 card-gradient-warning">
            <div class="card-body p-4">
                <div class="d-flex align-items-center">
                    <div class="bg-accent-warning p-3 rounded-3 me-3">
                        <i class="bi bi-envelope-exclamation-fill fs-3 text-warning"></i>
                    </div>
                    <div>
                        <h6 class="text-warning small fw-bold text-uppercase mb-1">Pengaduan Baru</h6>
                        <h2 class="fw-bold mb-0 text-warning"><?= $pengaduan_masuk ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($maintenance_alerts)): ?>
<div class="row mb-5">
    <div class="col-12">
        <div class="card border-0 bg-accent-warning bg-opacity-10" style="border-left: 5px solid #ffc107 !important;">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center fw-bold py-3">
                <span><i class="bi bi-megaphone-fill text-accent-warning me-2"></i> Peringatan Maintenance</span>
                <a href="<?= base_url('admin/maintenance/upcoming') ?>" class="btn btn-sm btn-outline-warning">Lihat Semua</a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <?php foreach (array_slice($maintenance_alerts, 0, 4) as $alert): ?>
                        <div class="col-md-3">
                            <div class="p-3 bg-white rounded-3 shadow-sm border-start border-4 <?= $alert['reminder_type'] == 'overdue' ? 'border-danger' : 'border-warning' ?>">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <span class="badge <?= $alert['reminder_type'] == 'overdue' ? 'bg-danger' : 'bg-warning' ?> text-dark">
                                        <?= strtoupper($alert['reminder_type']) ?>
                                    </span>
                                    <small class="text-muted small"><?= date('d M', strtotime($alert['due_date'])) ?></small>
                                </div>
                                <h6 class="fw-bold mb-1 text-truncate"><?= esc($alert['nama_barang']) ?></h6>
                                <p class="mb-0 small text-muted"><?= $alert['kode'] ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="row">
    <!-- Chart -->
    <div class="col-md-7 mb-4">
        <div class="card h-100 border-0">
            <div class="card-header bg-transparent fw-bold py-3">
                <i class="bi bi-graph-up text-accent-primary me-2"></i> Grafik Peminjaman Tahunan
            </div>
            <div class="card-body">
                <canvas id="peminjamanChart" style="max-height: 400px;"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Recent Activity -->
    <div class="col-md-5 mb-4">
        <div class="card h-100 border-0">
            <div class="card-header bg-transparent d-flex justify-content-between align-items-center fw-bold py-3">
                <span><i class="bi bi-clock-history text-primary me-2"></i> Aktivitas Terkini</span>
                <a href="<?= base_url('admin/log') ?>" class="btn btn-sm btn-link text-decoration-none p-0">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (empty($recent_activities)): ?>
                        <div class="p-5 text-center text-muted">Belum ada aktivitas.</div>
                    <?php endif; ?>
                    <?php foreach ($recent_activities as $activity): ?>
                        <div class="list-group-item py-3 px-4">
                            <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                                <h6 class="mb-0 fw-bold"><?= esc($activity['aksi']) ?></h6>
                                <small class="text-muted small"><?= date('H:i', strtotime($activity['created_at'])) ?></small>
                            </div>
                            <p class="mb-2 small text-muted"><?= esc($activity['deskripsi']) ?></p>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($activity['nama_lengkap']) ?>&size=20&background=random" class="rounded-circle me-2" width="20" height="20">
                                <small class="text-primary fw-medium"><?= esc($activity['nama_lengkap']) ?></small>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('peminjamanChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?= $chart_data ?>,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(255, 255, 255, 0.05)'
                    },
                    ticks: {
                        color: '#b5b5c3',
                        stepSize: 1
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#b5b5c3'
                    }
                }
            }
        }
    });
</script>

<?= $this->endSection() ?>
