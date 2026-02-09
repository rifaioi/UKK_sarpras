<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title') ?>Analytics & Lifecycle<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Internal Analytics & Asset Lifecycle</h1>
</div>

<!-- TOP CHARTS -->
<div class="row mb-4">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">Trend Kerusakan (6 Bulan Terakhir)</div>
            <div class="card-body">
                <canvas id="trendChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">Kerusakan per Kategori</div>
            <div class="card-body">
                <canvas id="categoryChart"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">Top 10 Alat Paling Sering Rusak</div>
            <div class="card-body">
                <canvas id="topDamagedChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-dark text-white">Kerusakan per Peminjam (Top 5)</div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(array_slice($damage_by_user, 0, 5) as $user): ?>
                            <tr>
                                <td><?= esc($user['user_name']) ?></td>
                                <td><span class="badge bg-danger"><?= $user['damage_count'] ?>x</span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- LIFECYCLE TABLE -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">Asset Lifecycle & Recommendations</div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Barang</th>
                        <th>Kategori</th>
                        <th>Umur (Bulan)</th>
                        <th>Persen Umur</th>
                        <th>Total Perawatan</th>
                        <th>Rekomendasi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($lifecycle_data as $item): ?>
                    <tr>
                        <td>
                            <strong><?= esc($item['nama']) ?></strong><br>
                            <small class="text-muted"><?= esc($item['kode']) ?></small>
                        </td>
                        <td><?= esc($item['category']) ?></td>
                        <td><?= $item['age_months'] ?> / <?= $item['lifespan_months'] ?></td>
                        <td>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-<?= $item['usage_percent'] > 90 ? 'danger' : ($item['usage_percent'] > 70 ? 'warning' : 'success') ?>" 
                                     role="progressbar" style="width: <?= min($item['usage_percent'], 100) ?>%"></div>
                            </div>
                            <small><?= $item['usage_percent'] ?>%</small>
                        </td>
                        <td>Rp <?= number_format($item['cost'], 0, ',', '.') ?></td>
                        <td><span class="badge bg-<?= $item['badge_color'] ?>"><?= $item['recommendation'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php if (isset($pager)): ?>
        <div class="card-footer d-flex justify-content-center">
            <?= $pager ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- SCRIPTS FOR CHARTS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // 1. Trend Chart
    const trendCtx = document.getElementById('trendChart').getContext('2d');
    new Chart(trendCtx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_column($damage_trend, 'month')) ?>,
            datasets: [{
                label: 'Jumlah Kerusakan',
                data: <?= json_encode(array_column($damage_trend, 'damage_count')) ?>,
                borderColor: '#dc3545',
                tension: 0.1,
                fill: true,
                backgroundColor: 'rgba(220, 53, 69, 0.1)'
            }]
        },
        options: { responsive: true }
    });

    // 2. Category Chart
    const catCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(catCtx, {
        type: 'pie',
        data: {
            labels: <?= json_encode(array_column($damage_by_category, 'category_name')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($damage_by_category, 'damage_count')) ?>,
                backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#6c757d', '#0dcaf0']
            }]
        }
    });

    // 3. Top Damaged Chart
    const damagedCtx = document.getElementById('topDamagedChart').getContext('2d');
    new Chart(damagedCtx, {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_column($top_damaged, 'nama')) ?>,
            datasets: [{
                label: 'Frekuensi Rusak',
                data: <?= json_encode(array_column($top_damaged, 'damage_count')) ?>,
                backgroundColor: '#dc3545'
            }]
        }
    });
</script>

<?= $this->endSection() ?>
