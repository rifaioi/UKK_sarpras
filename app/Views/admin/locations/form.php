<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?><?= isset($location) ? 'Edit Lokasi' : 'Tambah Lokasi' ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($location) ? 'Edit Lokasi' : 'Tambah Lokasi' ?></h1>
</div>

<div class="row">
    <div class="col-md-6">
        <form action="<?= isset($location) ? base_url('admin/locations/update/'.$location['id']) : base_url('admin/locations/store') ?>" method="post" class="form-confirm">
            <div class="mb-3">
                <label class="form-label">Nama Lokasi</label>
                <input type="text" class="form-control" name="nama_lokasi" value="<?= isset($location) ? $location['nama_lokasi'] : old('nama_lokasi') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Keterangan</label>
                <textarea class="form-control" name="keterangan" rows="3"><?= isset($location) ? $location['keterangan'] : old('keterangan') ?></textarea>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary px-4">Simpan</button>
                <a href="<?= base_url('admin/locations') ?>" class="btn btn-action px-4 d-flex align-items-center">Batal</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
