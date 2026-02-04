<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Pengembalian<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Proses Pengembalian</h1>
    <div class="d-flex">
        <a href="<?= base_url('admin/pengembalian/scan') ?>" class="btn btn-sm btn-primary me-2 fw-bold">
            <i class="bi bi-qr-code-scan me-1"></i> Scan QR Peminjaman
        </a>
        <a href="<?= base_url('admin/pengembalian/riwayat') ?>" class="btn btn-sm btn-info fw-bold">
            <i class="bi bi-clock-history me-1"></i> Lihat Riwayat
        </a>
    </div>
</div>



<div class="table-responsive">
    <table class="table table-striped table-sm">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Deadline</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($active_peminjaman as $p): ?>
            <tr>
                <td><?= $p['id'] ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['nama_barang']) ?></td>
                <td><?= esc($p['jumlah']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_kembali_rencana'])) ?></td>
                <td>
                    <a href="<?= base_url('admin/pengembalian/process/'.$p['id']) ?>" class="btn btn-sm btn-primary">Proses Kembali</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
