<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>Inventaris Sarpras<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventaris Sarpras</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/sarpras/create') ?>" class="btn btn-tambah btn-sm-tambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="" method="get" class="d-flex">
            <input type="text" name="q" class="form-control me-2" placeholder="Cari nama barang..." value="<?= esc($q ?? '') ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q): ?>
                <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-outline-secondary ms-2">Reset</a>
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
                <th>Total Unit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
            <tr>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($item['nama']) ?></td>
                <td><?= esc($item['nama_kategori']) ?></td>
                <td>
                    <span class="badge bg-success"><?= esc($item['tersedia']) ?> Tersedia</span>
                    <span class="badge bg-secondary"><?= esc($item['total_unit']) ?> Total</span>
                </td>
                <td>
                    <div class="d-flex gap-1">
                        <a href="<?= base_url('admin/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="btn btn-action btn-sm btn-info text-white" title="Lihat Unit">
                            <i class="bi bi-list-ul"></i> Lihat Unit
                        </a>
                        <a href="<?= base_url('admin/sarpras/delete_group/'.$item['kategori_id'].'?nama='.urlencode($item['nama'])) ?>" class="btn btn-action text-danger" title="Hapus Grup" onclick="return confirm('Yakin ingin menghapus SEMUA unit dari barang ini?')">
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
