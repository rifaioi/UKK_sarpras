<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Manajemen Lokasi<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Lokasi</h1>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <a href="<?= base_url('admin/locations/trash') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-trash"></i> Recycle Bin
        </a>
        <a href="<?= base_url('admin/locations/create') ?>" class="btn btn-tambah btn-sm-tambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Lokasi
        </a>
    </div>
</div>



<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lokasi</th>
                <th>Keterangan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($locations as $i => $loc): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($loc['nama_lokasi']) ?></td>
                <td><?= esc($loc['keterangan']) ?></td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="<?= base_url('admin/locations/edit/'.$loc['id']) ?>" class="btn btn-action text-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('admin/locations/delete/'.$loc['id']) ?>" class="btn btn-action text-danger btn-delete" title="Delete">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
