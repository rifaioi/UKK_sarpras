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
                <?php if($sarpras): ?>
                <!-- Info Barang -->
                <div class="alert alert-info mb-4">
                    <h6><i class="bi bi-box-seam me-2"></i> Barang yang di-Maintenance:</h6>
                    <p class="mb-1"><strong><?= esc($sarpras['nama']) ?></strong> (<?= esc($sarpras['kode']) ?>)</p>
                    <?php if($sarpras['maintenance_interval']): ?>
                        <small class="text-muted">Interval Maintenance: Setiap <?= $sarpras['maintenance_interval'] ?> bulan</small>
                    <?php else: ?>
                        <small class="text-warning">⚠️ Barang ini belum memiliki jadwal maintenance rutin</small>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <form action="<?= base_url($role.'/maintenance/records/store') ?>" method="post">
                    <?= csrf_field() ?>
                    
                    <?php if($sarpras): ?>
                        <input type="hidden" name="sarpras_id" value="<?= $sarpras['id'] ?>">
                    <?php else: ?>
                    <div class="mb-3">
                        <label class="form-label">Pilih Barang</label>
                        <select name="sarpras_id" class="form-select" required>
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($all_sarpras as $item): ?>
                            <option value="<?= $item['id'] ?>">
                                <?= esc($item['nama']) ?> (<?= esc($item['kode']) ?>)
                                <?= $item['maintenance_interval'] ? ' - Interval: '.$item['maintenance_interval'].' bulan' : '' ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>

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
                        <label class="form-label">Biaya (Opsional)</label>
                        <input type="number" name="cost" class="form-control" step="0.01" placeholder="0">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Tambahan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Observasi, rekomendasi, atau catatan khusus"></textarea>
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
