<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Daftar Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Peminjaman Aktif (Belum Kembali)</h1>
    <div class="d-flex">
        <a href="<?= base_url('petugas/pengembalian/scan') ?>" class="btn btn-sm btn-primary me-2">
            <i class="bi bi-qr-code-scan me-1"></i> Scan QR Peminjaman
        </a>
        <a href="<?= base_url('petugas/pengembalian/riwayat') ?>" class="btn btn-sm btn-info">
            <i class="bi bi-clock-history me-1"></i> Lihat Riwayat
        </a>
    </div>
</div>



<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
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
            <?php foreach($active_peminjaman as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['nama_barang']) ?></td>
                <td><?= esc($p['jumlah']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_kembali_rencana'])) ?></td>
                <td>
                    <a href="<?= base_url('petugas/pengembalian/process/'.$p['id']) ?>" class="btn btn-sm btn-primary">Proses Kembali</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
