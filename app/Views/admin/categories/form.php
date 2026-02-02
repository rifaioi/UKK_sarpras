<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?><?= isset($category) ? 'Edit Kategori' : 'Tambah Kategori' ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($category) ? 'Edit Kategori' : 'Tambah Kategori' ?></h1>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?= isset($category) ? base_url('admin/categories/update/'.$category['id']) : base_url('admin/categories/store') ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Nama Kategori</label>
                <input type="text" class="form-control" name="nama" value="<?= isset($category) ? $category['nama'] : old('nama') ?>" required placeholder="Contoh: Laptop, Meja, Proyektor">
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="<?= base_url('admin/categories') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
