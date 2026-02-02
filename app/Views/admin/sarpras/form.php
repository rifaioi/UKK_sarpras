<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>
    <?= isset($item) ? 'Edit Sarpras' : 'Tambah Sarpras' ?>
<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($item) ? 'Edit Sarpras' : 'Tambah Sarpras' ?></h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0">
            <div class="card-body p-4">
                <form action="<?= isset($item) ? base_url('admin/sarpras/update/'.$item['id']) : base_url('admin/sarpras/store') ?>" method="post">
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kode Inventaris (Auto)</label>
                            <input type="text" class="form-control bg-dark text-muted border-secondary opacity-50" value="<?= isset($item) ? $item['kode'] : '' ?>" placeholder="Akan digenerate otomatis" readonly>
                            <small class="text-muted">Kode digenerate berdasarkan kategori.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Nama Barang</label>
                            <input type="text" class="form-control bg-dark text-white border-secondary" name="nama" value="<?= isset($item) ? $item['nama'] : old('nama') ?>" required placeholder="Contoh: Laptop ASUS ROG, Meja Guru">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Kategori</label>
                            <select class="form-select bg-dark text-white border-secondary" name="kategori_id" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (isset($item) && $item['kategori_id'] == $cat['id']) ? 'selected' : '' ?>><?= $cat['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label text-white">Lokasi</label>
                            <select class="form-select bg-dark text-white border-secondary" name="location_id" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>" <?= (isset($item) && $item['location_id'] == $loc['id']) ? 'selected' : '' ?>><?= $loc['nama_lokasi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-white">Stok</label>
                            <input type="number" class="form-control bg-dark text-white border-secondary" name="stok" value="<?= isset($item) ? $item['stok'] : (old('stok') ?? 0) ?>" required min="0">
                        </div>
                        <div class="col-md-6 mb-4">
                            <label class="form-label text-white">Kondisi</label>
                            <select class="form-select bg-dark text-white border-secondary" name="kondisi_id" required>
                                <?php foreach($conditions as $cond): ?>
                                    <option value="<?= $cond['id'] ?>" <?= (isset($item) && $item['kondisi_id'] == $cond['id']) ? 'selected' : '' ?>><?= $cond['nama_kondisi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="pt-3 border-top border-secondary border-opacity-10">
                        <button type="submit" class="btn btn-primary px-5 rounded-pill me-2">
                            <i class="bi bi-save me-2"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-outline-secondary px-4 rounded-pill">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
