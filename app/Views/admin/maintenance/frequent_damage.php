<?= $this->extend(session()->get('role_id') == 1 ? 'admin/layout' : 'petugas/layout') ?>

<?= $this->section('page_title') ?>Unit Sering Rusak<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Analisis Unit Paling Sering Rusak</h1>
    <div class="text-muted small">Berdasarkan data frekuensi kerusakan saat pengembalian</div>
</div>

<div class="card shadow-sm border-0 bg-dark-glass">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-dark">
                    <tr>
                        <th style="width: 50px;">Rank</th>
                        <th>Kode/Nama Unit</th>
                        <th>Kategori</th>
                        <th class="text-center">Total Pinjam</th>
                        <th class="text-center">Frekuensi Rusak</th>
                        <th class="text-center">Tingkat Kerusakan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($breakdowns)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-4 text-muted">Belum ada data unit yang tercatat mengalami kerusakan.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach($breakdowns as $i => $b): 
                            $ratio = ($b['total_kembali'] > 0) ? ($b['damage_count'] / $b['total_kembali']) * 100 : 0;
                            $badgeColor = $ratio > 50 ? 'danger' : ($ratio > 25 ? 'warning' : 'info');
                        ?>
                        <tr>
                            <td class="text-center fw-bold text-white"><?= $i+1 ?></td>
                            <td>
                                <strong class="text-white"><?= esc($b['kode']) ?></strong><br>
                                <small class="text-muted"><?= esc($b['nama_barang']) ?></small>
                            </td>
                            <td><?= esc($b['kategori_nama']) ?></td>
                            <td class="text-center"><?= $b['total_kembali'] ?>x</td>
                            <td class="text-center">
                                <span class="badge bg-danger"><?= $b['damage_count'] ?>x Rusak</span>
                            </td>
                            <td class="text-center">
                                <div class="progress" style="height: 8px; width: 100px; margin: 0 auto;">
                                    <div class="progress-bar bg-<?= $badgeColor ?>" style="width: <?= min($ratio, 100) ?>%"></div>
                                </div>
                                <small class="text-<?= $badgeColor ?>"><?= round($ratio, 1) ?>%</small>
                            </td>
                            <td>
                                <a href="<?= base_url((session()->get('role_id') == 1 ? 'admin' : 'petugas') . '/maintenance/records/history/'.$b['sarpras_id']) ?>" class="btn btn-sm btn-outline-info">Riwayat</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>



<?= $this->endSection() ?>
