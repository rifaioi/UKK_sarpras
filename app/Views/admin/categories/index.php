<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Manajemen Kategori<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Kategori</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/categories/create') ?>" class="btn btn-tambah btn-sm-tambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Kategori
        </a>
    </div>
</div>



<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Kategori</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($categories as $i => $cat): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($cat['nama']) ?></td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="<?= base_url('admin/categories/edit/'.$cat['id']) ?>" class="btn btn-action text-warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('admin/categories/delete/'.$cat['id']) ?>" class="btn btn-action text-danger" title="Delete">
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
