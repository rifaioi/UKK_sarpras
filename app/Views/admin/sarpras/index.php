<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>Inventaris Sarpras<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventaris Sarpras</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/sarpras/create') ?>" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-lg"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Lokasi</th>
                <th>Stok</th>
                <th>Kondisi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($item['kode']) ?></td>
                <td><?= esc($item['nama']) ?></td>
                <td><?= esc($item['nama_kategori']) ?></td>
                <td><?= esc($item['nama_lokasi']) ?></td>
                <td><?= esc($item['stok']) ?></td>
                <td>
                    <?php
                        $badge = $item['kondisi_id'] == 1 ? 'bg-success' : ($item['kondisi_id'] == 2 ? 'bg-warning text-dark' : 'bg-danger');
                    ?>
                    <span class="badge <?= $badge ?>"><?= esc($item['nama_kondisi']) ?></span>
                </td>
                <td>
                    <a href="<?= base_url('admin/sarpras/edit/'.$item['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= base_url('admin/sarpras/delete/'.$item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus data barang ini?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
