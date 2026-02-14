<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Riwayat Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Pengembalian & Kondisi Alat</h1>
</div>



<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Kode Unit</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($riwayat_pengembalian as $i => $r): ?>
            <tr>
                <td><?= count($riwayat_pengembalian) - $i ?></td>
                <td><?= esc($r['nama_lengkap']) ?></td>
                <td><?= esc($r['nama_barang']) ?></td>
                <td><code class="text-info"><?= esc($r['kode']) ?></code></td>
                <td><?= esc($r['jumlah']) ?></td>
                <td><?= date('d/m/Y', strtotime($r['tgl_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($r['tgl_pengembalian'])) ?></td>
                <td>
                    <?php 
                        $badgeClass = 'bg-secondary';
                        if($r['nama_kondisi'] == 'Baik') $badgeClass = 'bg-success';
                        elseif($r['nama_kondisi'] == 'Rusak Ringan') $badgeClass = 'bg-warning text-dark';
                        elseif($r['nama_kondisi'] == 'Rusak Berat') $badgeClass = 'bg-danger';
                        elseif($r['nama_kondisi'] == 'Hilang') $badgeClass = 'bg-dark';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= esc($r['nama_kondisi']) ?></span>
                </td>
                <td>
                    <a href="<?= base_url('petugas/pengembalian/detail/'.$r['id']) ?>" class="btn btn-sm btn-info">Detail</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
