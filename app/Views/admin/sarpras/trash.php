<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>Recycle Bin: Sarpras<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Recycle Bin: Sarpras</h1>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Data Aktif
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="" method="get" class="d-flex">
            <input type="text" name="q" class="form-control me-2" placeholder="Cari nama barang..." value="<?= esc($q ?? '') ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q): ?>
                <a href="<?= base_url('admin/sarpras/trash') ?>" class="btn btn-outline-secondary ms-2">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Kategori</th>
                <th>Jumlah Unit Terhapus</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($items)): ?>
                <tr>
                    <td colspan="5" class="text-center py-3 text-muted italic">Tidak ada data di tempat sampah.</td>
                </tr>
            <?php endif; ?>
            <?php foreach ($items as $i => $item): ?>
            <tr class="text-decoration-line-through text-muted">
                <td><?= $i+1 ?></td>
                <td><?= esc($item['nama']) ?></td>
                <td><?= esc($item['nama_kategori']) ?></td>
                <td>
                    <span class="badge bg-secondary"><?= esc($item['total_unit']) ?> Unit</span>
                </td>
                <td>
                    <div class="d-flex gap-1 justify-content-center">
                        <a href="<?= base_url('admin/sarpras/restore_group/'.$item['kategori_id'].'?nama='.urlencode($item['nama'])) ?>" class="btn btn-action text-success btn-confirm" title="Restore Grup">
                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                        </a>
                        <a href="<?= base_url('admin/sarpras/delete_group/'.$item['kategori_id'].'?nama='.urlencode($item['nama'])) ?>" class="btn btn-action text-danger btn-delete-permanent" title="Hapus Permanen" onclick="return confirm('Yakin ingin menghapus permanen grup ini? Data tidak bisa dikembalikan.')">
                            <i class="bi bi-trash"></i> Hapus
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
