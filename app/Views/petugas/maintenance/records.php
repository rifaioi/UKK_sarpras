<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Riwayat Maintenance<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Riwayat Maintenance</h1>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?= base_url('petugas/maintenance/records') ?>" method="get" class="row g-3">
            <div class="col-md-6">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 shadow-none" placeholder="Cari aset, teknisi..." value="<?= esc($filter_q) ?>">
                </div>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-none">Cari</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3">TANGGAL SELESAI</th>
                        <th>ASET</th>
                        <th>HASIL</th>
                        <th>KONDISI AKHIR</th>
                        <th>TEKNISI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($records)): ?>
                        <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat maintenance.</td></tr>
                    <?php else: ?>
                        <?php foreach($records as $r): ?>
                        <tr>
                            <td class="ps-3">
                                <div class="fw-bold"><?= date('d M Y', strtotime($r['completion_date'])) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold fs-6"><?= esc($r['asset_name']) ?></div>
                                <div class="text-muted small"><?= esc($r['asset_kode']) ?></div>
                            </td>
                            <td>
                                <div><?= esc($r['result']) ?></div>
                                <div class="text-muted smallest"><?= esc($r['notes']) ?></div>
                            </td>
                            <td>
                                <span class="badge bg-success small"><?= esc($r['nama_kondisi']) ?></span>
                            </td>
                            <td><?= esc($r['technician']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .smallest { font-size: 0.75rem; }
    .table thead th { border-top: none; font-size: 0.7rem; color: #6c757d; font-weight: 600; letter-spacing: 0.05em; }
</style>
<?= $this->endSection() ?>
