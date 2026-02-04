<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Laporan Kondisi Aset<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="row mb-4">
    <div class="col-12">
        <h3>Laporan Kondisi Aset (Asset Health)</h3>
    </div>
</div>

<div class="row g-3 mb-4">
    <?php foreach($conditions as $c): ?>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= esc($c['nama_kondisi']) ?></h5>
                <p class="card-text h3"><?= esc($c['jumlah']) ?></p>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<div class="row g-4 mb-5">
    <!-- Damaged Section -->
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-dark bg-opacity-25">
            <div class="card-header bg-transparent border-bottom border-secondary py-3">
                <h5 class="mb-0 text-white"><i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>Daftar Alat Rusak (Asset Health - Rusak)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm text-white border-secondary">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Barang</th>
                                <th>Lokasi</th>
                                <th>Sejak Tanggal</th>
                                <th>Peminjam Terakhir</th>
                                <th>Catatan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($damaged_items)): ?>
                                <tr><td colspan="7" class="text-center py-3 text-muted">Tidak ada alat rusak saat ini.</td></tr>
                            <?php endif; ?>
                            <?php foreach($damaged_items as $i => $item): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><code><?= esc($item['kode']) ?></code></td>
                                <td><?= esc($item['nama_barang']) ?></td>
                                <td><?= esc($item['nama_lokasi']) ?></td>
                                <td><?= date('d/m/Y', strtotime($item['tgl_pengembalian'])) ?></td>
                                <td><?= esc($item['peminjam']) ?></td>
                                <td class="small"><?= esc($item['deskripsi']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Missing Section -->
    <div class="col-md-7">
        <div class="card border-0 shadow-sm bg-dark bg-opacity-25 h-100">
            <div class="card-header bg-transparent border-bottom border-secondary py-3">
                <h5 class="mb-0 text-white"><i class="bi bi-search text-danger me-2"></i>Daftar Alat Hilang (Asset Health - Hilang)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm text-white border-secondary">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Barang</th>
                                <th>Tanggal Kejadian</th>
                                <th>Peminjam Terakhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($missing_items)): ?>
                                <tr><td colspan="5" class="text-center py-3 text-muted">Tidak ada data alat hilang.</td></tr>
                            <?php endif; ?>
                            <?php foreach($missing_items as $i => $item): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><code><?= esc($item['kode']) ?></code></td>
                                <td><?= esc($item['nama_barang']) ?></td>
                                <td><?= date('d/m/Y', strtotime($item['tgl_pengembalian'])) ?></td>
                                <td><?= esc($item['peminjam']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Top 10 Damaged -->
    <div class="col-md-5">
        <div class="card border-0 shadow-sm bg-dark bg-opacity-25 h-100">
            <div class="card-header bg-transparent border-bottom border-secondary py-3">
                <h5 class="mb-0 text-white"><i class="bi bi-graph-down text-info me-2"></i>Top 10 Alat Sering Rusak</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-sm text-white border-secondary">
                        <thead>
                            <tr>
                                <th>Ranking</th>
                                <th>Nama Barang</th>
                                <th>Total Kerusakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($top_damaged)): ?>
                                <tr><td colspan="3" class="text-center py-3 text-muted">Belum ada data kerusakan.</td></tr>
                            <?php endif; ?>
                            <?php foreach($top_damaged as $i => $item): ?>
                            <tr>
                                <td><span class="badge <?= $i < 3 ? 'bg-danger' : 'bg-secondary' ?>"><?= $i+1 ?></span></td>
                                <td><?= esc($item['nama_barang']) ?></td>
                                <td class="text-end fw-bold"><?= esc($item['total_kerusakan']) ?> kali</td>
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
