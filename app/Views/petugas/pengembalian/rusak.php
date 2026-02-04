<?= $this->extend('petugas/layout') ?>
<?= $this->section('page_title'); ?>
    Sedang Dalam Perbaikan
<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sedang Dalam Perbaikan</h1>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if(empty($barang_rusak)): ?>
            <div class="alert alert-info border-0 bg-dark bg-opacity-25 text-white">Tidak ada barang yang sedang dalam perbaikan.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-striped table-sm text-white">
                    <thead>
                        <tr>
                            <th>Tanggal Kembali</th>
                            <th>Peminjam</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Kondisi Pengembalian</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($barang_rusak as $item): ?>
                        <tr>
                            <td><?= date('d/m/Y', strtotime($item['tgl_pengembalian'])) ?></td>
                            <td><?= esc($item['nama_lengkap']) ?></td>
                            <td><?= esc($item['nama_barang']) ?></td>
                            <td><span class="badge bg-secondary"><?= esc($item['jumlah']) ?></span></td>
                            <td><span class="badge bg-danger"><?= esc($item['nama_kondisi']) ?></span></td>
                            <td>
                                <a href="<?= base_url('petugas/pengembalian/restock/' . $item['id']) ?>" 
                                   class="btn btn-sm btn-success mb-1"
                                   onclick="return confirm('Apakah barang ini sudah diperbaiki dan siap dimasukkan kembali ke stok?')">
                                    <i class="bi bi-tools me-1"></i> Selesai
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
