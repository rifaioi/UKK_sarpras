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
                            <input type="text" class="form-control text-muted" value="<?= isset($item) ? $item['kode'] : '' ?>" placeholder="Akan digenerate otomatis" readonly title="Generated automatically">
                            <small class="text-muted">Kode digenerate dari Kategori, Lokasi, dan Nama Barang.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Barang</label>
                            <input type="text" class="form-control" 
                                   name="nama" value="<?= isset($item) ? $item['nama'] : old('nama') ?>" 
                                   required placeholder="Contoh: Laptop ASUS ROG"
                                   <?= isset($item) ? 'readonly' : '' ?>>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori</label>
                            <?php if(isset($item)): ?>
                                <input type="hidden" name="kategori_id" value="<?= $item['kategori_id'] ?>">
                            <?php endif; ?>
                            <select class="form-select" name="kategori_id" required <?= isset($item) ? 'disabled' : '' ?>>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" <?= (isset($item) && $item['kategori_id'] == $cat['id']) ? 'selected' : '' ?>><?= $cat['nama'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Lokasi</label>
                            <select class="form-select" name="location_id" required>
                                <option value="">-- Pilih Lokasi --</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>" <?= (isset($item) && $item['location_id'] == $loc['id']) ? 'selected' : '' ?>><?= $loc['nama_lokasi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="parent_id" class="form-label">Grup Barang (Optional)</label>
                        <select name="parent_id" class="form-select">
                            <option value="">-- Barang Utama (Bukan Sub-Item) --</option>
                            <?php foreach($all_sarpras as $p): ?>
                                <option value="<?= $p['id'] ?>" <?= (isset($item) && $item['parent_id'] == $p['id']) ? 'selected' : '' ?>><?= esc($p['nama']) ?> (<?= $p['kode'] ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tgl_pengadaan" class="form-label">Tanggal Pengadaan</label>
                            <input type="date" name="tgl_pengadaan" class="form-control" id="tgl_pengadaan" value="<?= isset($item) ? $item['tgl_pengadaan'] : '' ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="harga_beli" class="form-label">Harga Beli (Rp)</label>
                            <input type="number" name="harga_beli" step="0.01" class="form-control" id="harga_beli" value="<?= isset($item) ? $item['harga_beli'] : '0' ?>">
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-12 mb-4">
                            <label class="form-label">Kondisi <span class="text-danger">*</span></label>
                            <select class="form-select" name="kondisi_id" required>
                                <?php foreach($conditions as $cond): ?>
                                    <option value="<?= $cond['id'] ?>" <?= (isset($item) && $item['kondisi_id'] == $cond['id']) ? 'selected' : '' ?>><?= $cond['nama_kondisi'] ?></option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Setiap unit disimpan sebagai data terpisah agar bisa memiliki kondisi yang berbeda.</small>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Interval Maintenance (Opsional)</label>
                            <select class="form-select" name="maintenance_interval">
                                <option value="">-- Tidak Ada Jadwal --</option>
                                <option value="1" <?= (isset($item) && $item['maintenance_interval'] == 1) ? 'selected' : '' ?>>Setiap 1 Bulan</option>
                                <option value="3" <?= (isset($item) && $item['maintenance_interval'] == 3) ? 'selected' : '' ?>>Setiap 3 Bulan</option>
                                <option value="6" <?= (isset($item) && $item['maintenance_interval'] == 6) ? 'selected' : '' ?>>Setiap 6 Bulan</option>
                                <option value="12" <?= (isset($item) && $item['maintenance_interval'] == 12) ? 'selected' : '' ?>>Setiap 12 Bulan (1 Tahun)</option>
                            </select>
                        </div>
                        <?php if(isset($item) && $item['next_maintenance_date']): ?>
                        <div class="col-md-6 mb-4">
                            <label class="form-label">Maintenance Berikutnya</label>
                            <input type="text" class="form-control text-warning" value="<?= date('d/m/Y', strtotime($item['next_maintenance_date'])) ?>" readonly>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="pt-3 border-top border-secondary border-opacity-10 d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="bi bi-save me-2"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-action px-4 d-flex align-items-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
