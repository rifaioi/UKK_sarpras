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

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Barang</th>
                <th>Peminjam</th>
                <th>Kondisi</th>
                <th>Detail</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($damaged_items as $i => $item): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= date('d/m/Y', strtotime($item['tgl_pengembalian'])) ?></td>
                <td><?= esc($item['nama_barang']) ?></td>
                <td><?= esc($item['peminjam']) ?></td>
                <td>
                    <span class="badge bg-danger"><?= esc($item['nama_kondisi']) ?></span>
                </td>
                <td><?= esc($item['deskripsi']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
