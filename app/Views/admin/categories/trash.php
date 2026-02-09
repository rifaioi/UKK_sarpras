<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Recycle Bin: Kategori<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Recycle Bin: Kategori</h1>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Data Aktif
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
            <?php if(empty($categories)): ?>
                <tr>
                    <td colspan="3" class="text-center py-3 text-muted italic">Tidak ada data di tempat sampah.</td>
                </tr>
            <?php endif; ?>
            <?php foreach($categories as $i => $cat): ?>
            <tr class="text-decoration-line-through text-muted">
                <td><?= $i+1 ?></td>
                <td><?= esc($cat['nama']) ?></td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="<?= base_url('admin/categories/restore/'.$cat['id']) ?>" class="btn btn-action text-success btn-confirm" title="Restore">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
