<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title') ?><?= isset($schedule) ? 'Edit' : 'Tambah' ?> Jadwal Maintenance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2"><?= isset($schedule) ? 'Edit' : 'Tambah' ?> Jadwal Maintenance</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= isset($schedule) ? base_url('admin/maintenance/schedules/update/'.$schedule['id']) : base_url('admin/maintenance/schedules/store') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Kategori Alat</label>
                        <select name="kategori_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (isset($schedule) && $schedule['kategori_id'] == $cat['id']) ? 'selected' : '' ?>>
                                <?= esc($cat['nama']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Jadwal</label>
                        <input type="text" name="schedule_name" class="form-control" 
                               value="<?= isset($schedule) ? esc($schedule['schedule_name']) : '' ?>" 
                               placeholder="Contoh: Pembersihan Rutin, Cek Lampu, dll" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Interval (Bulan)</label>
                        <input type="number" name="interval_months" class="form-control" 
                               value="<?= isset($schedule) ? $schedule['interval_months'] : '3' ?>" 
                               min="1" required>
                        <small class="text-muted">Setiap berapa bulan maintenance harus dilakukan</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="4"><?= isset($schedule) ? esc($schedule['description']) : '' ?></textarea>
                        <small class="text-muted">Apa yang perlu dilakukan saat maintenance</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="<?= base_url('admin/maintenance/schedules') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
