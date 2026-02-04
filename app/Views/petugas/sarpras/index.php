<?= $this->extend('petugas/layout') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Barang</h1>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Total Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($items as $i => $item): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($item['nama']) ?></td>
                <td><?= esc($item['nama_kategori']) ?></td>
                <td>
                    <span class="badge bg-success"><?= esc($item['tersedia']) ?> Tersedia</span>
                    <span class="badge bg-secondary"><?= esc($item['total_unit']) ?> Total</span>
                </td>
                <td>
                    <a href="<?= base_url('petugas/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="btn btn-sm btn-info text-white" title="Lihat Unit">
                        <i class="bi bi-list-ul"></i> Lihat Unit
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
