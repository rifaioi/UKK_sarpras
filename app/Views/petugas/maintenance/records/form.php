<?php 
$layout = (session()->get('role_id') == 1) ? 'admin/layout' : 'petugas/layout';
$role = (session()->get('role_id') == 1) ? 'admin' : 'petugas';
?>
<?= $this->extend($layout) ?>
<?= $this->section('page_title') ?>Catat Maintenance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Catat Maintenance</h1>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form action="<?= base_url($role.'/maintenance/records/store') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label">Barang</label>
                        <?php if($sarpras): ?>
                            <input type="hidden" name="sarpras_id" value="<?= $sarpras['id'] ?>">
                            <input type="text" class="form-control" value="<?= esc($sarpras['nama']) ?> (<?= esc($sarpras['kode']) ?>)" readonly>
                        <?php else: ?>
                            <select name="sarpras_id" class="form-select" required onchange="loadSchedules(this.value)">
                                <option value="">-- Pilih Barang --</option>
                                <?php foreach($all_sarpras as $item): ?>
                                <option value="<?= $item['id'] ?>"><?= esc($item['nama']) ?> (<?= esc($item['kode']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jadwal (Opsional)</label>
                        <select name="schedule_id" class="form-select">
                            <option value="">-- Tidak Ada Jadwal / Ad-hoc --</option>
                            <?php foreach($schedules as $schedule): ?>
                            <option value="<?= $schedule['id'] ?>"><?= esc($schedule['schedule_name']) ?> (<?= $schedule['interval_months'] ?> bulan)</option>
                            <?php endforeach; ?>
                        </select>
                        <small class="text-muted">Jika dipilih, sistem akan otomatis hitung tanggal maintenance berikutnya</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Maintenance</label>
                        <input type="date" name="maintenance_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Teknisi</label>
                        <input type="text" name="performed_by" class="form-control" placeholder="Nama teknisi yang melakukan maintenance" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Maintenance</label>
                        <textarea name="description" class="form-control" rows="4" placeholder="Jelaskan apa yang dilakukan saat maintenance" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-primary fw-bold">Update Kondisi Setelah Perawatan (Opsional)</label>
                        <select name="new_kondisi_id" class="form-select border-primary">
                            <option value="">-- Tetap Gunakan Kondisi Saat Ini --</option>
                            <option value="1">Baik (Barang bisa digunakan kembali)</option>
                            <option value="2">Rusak Ringan</option>
                            <option value="3">Rusak Berat / Perlu Ganti</option>
                        </select>
                        <small class="text-primary italic">Jika dipilih "Baik", sistem otomatis mengaktifkan barang ke status 'Tersedia'.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biaya (Opsional)</label>
                        <input type="number" name="cost" class="form-control" step="0.01" placeholder="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-control" rows="3"></textarea>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save"></i> Simpan
                        </button>
                        <a href="<?= base_url($role.'/maintenance/records') ?>" class="btn btn-secondary">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
