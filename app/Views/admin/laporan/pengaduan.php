<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Laporan Pengaduan<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Pengaduan</h1>
</div>

<style>
    @media print {
        .sidebar, .topbar, .d-print-none {
            display: none !important;
        }
        .content-wrapper {
            margin-left: 0 !important;
            margin-top: 0 !important;
        }
    }
</style>

<div class="card mb-4 d-print-none">
    <div class="card-body">
        <form action="<?= base_url('admin/reports/pengaduan') ?>" method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="<?= $filter['tgl_awal'] ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="<?= $filter['tgl_akhir'] ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Filter Status</label>
                <select name="status_id" class="form-select">
                    <option value="">-- Semua Status --</option>
                    <?php foreach($statuses as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $filter['status_id'] == $s['id'] ? 'selected' : '' ?>><?= $s['nama_status'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-tambah btn-sm-tambah fw-bold h-100 flex-grow-1">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
                <a href="<?= base_url('admin/reports/pengaduan') ?>" class="btn btn-action px-3 h-100 d-flex align-items-center">Reset</a>
            </div>
            <div class="col-md-12 d-flex justify-content-end mt-2">
                <?php 
                    $params = http_build_query(array_merge($filter, ['print' => 1]));
                ?>
                <a href="<?= base_url('admin/reports/pengaduan?' . $params) ?>" target="_blank" class="btn btn-action text-success fw-bold px-3">
                    <i class="bi bi-printer me-1"></i> Cetak PDF
                </a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Pelapor</th>
                <th>Judul</th>
                <th>Lokasi</th>
                <th>Tgl Lapor</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pengaduan as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['judul']) ?></td>
                <td><?= esc($p['lokasi']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($p['created_at'])) ?></td>
                <td>
                    <span class="badge bg-secondary"><?= esc($p['nama_status']) ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
