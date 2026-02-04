<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Detail Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Detail Pengembalian & Inspeksi Kondisi Alat</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Informasi Peminjaman</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Peminjam</th>
                        <td><?= esc($pengembalian['nama_lengkap']) ?></td>
                    </tr>
                    <tr>
                        <th>Barang</th>
                        <td><?= esc($pengembalian['nama_barang']) ?></td>
                    </tr>
                    <tr>
                        <th>Jumlah</th>
                        <td><?= esc($pengembalian['jumlah']) ?></td>
                    </tr>
                    <tr>
                        <th>Tanggal Pinjam</th>
                        <td><?= date('d/m/Y', strtotime($pengembalian['tgl_pinjam'])) ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Informasi Pengembalian</h5>
                <table class="table table-sm">
                    <tr>
                        <th>Tanggal Kembali</th>
                        <td><?= date('d/m/Y H:i', strtotime($pengembalian['created_at'])) ?></td>
                    </tr>
                    <tr>
                        <th>Kondisi Alat</th>
                        <td>
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if($pengembalian['nama_kondisi'] == 'Baik') $badgeClass = 'bg-success';
                                elseif($pengembalian['nama_kondisi'] == 'Rusak Ringan') $badgeClass = 'bg-warning text-dark';
                                elseif($pengembalian['nama_kondisi'] == 'Rusak Berat') $badgeClass = 'bg-danger';
                                elseif($pengembalian['nama_kondisi'] == 'Hilang') $badgeClass = 'bg-dark';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= esc($pengembalian['nama_kondisi']) ?></span>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Laporan Kerusakan / Deskripsi</h5>
                <?php if(!empty($pengembalian['deskripsi'])): ?>
                    <p><?= nl2br(esc($pengembalian['deskripsi'])) ?></p>
                <?php else: ?>
                    <p class="text-muted">Tidak ada deskripsi</p>
                <?php endif; ?>
            </div>
        </div>

        <?php if(!empty($pengembalian['foto'])): ?>
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Foto Bukti</h5>
                <img src="<?= base_url('uploads/pengembalian/'.$pengembalian['foto']) ?>" class="img-fluid" alt="Foto Pengembalian" style="max-width: 100%; max-height: 400px;">
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="mt-3">
    <a href="<?= base_url('petugas/pengembalian/riwayat') ?>" class="btn btn-secondary">Kembali ke Riwayat</a>
</div>
<?= $this->endSection() ?>
