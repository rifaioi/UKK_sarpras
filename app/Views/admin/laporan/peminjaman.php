<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Laporan Peminjaman<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Laporan Peminjaman</h1>
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
        <form action="<?= base_url('admin/reports/peminjaman') ?>" method="get" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tanggal Awal</label>
                <input type="date" name="tgl_awal" class="form-control" value="<?= $filter['tgl_awal'] ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Akhir</label>
                <input type="date" name="tgl_akhir" class="form-control" value="<?= $filter['tgl_akhir'] ?>">
            </div>
            <div class="col-md-5 d-flex align-items-end">
                <button type="submit" class="btn btn-primary me-2 fw-bold">Filter</button>
                <a href="<?= base_url('admin/reports/peminjaman') ?>" class="btn btn-secondary me-2">Reset</a>
                <a href="<?= base_url('admin/reports/peminjaman?print=1&tgl_awal='.$filter['tgl_awal'].'&tgl_akhir='.$filter['tgl_akhir']) ?>" target="_blank" class="btn btn-success fw-bold">
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
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($peminjaman as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['nama_barang']) ?></td>
                <td><?= esc($p['jumlah']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_kembali_rencana'])) ?></td>
                <td>
                    <span class="badge bg-secondary"><?= esc($p['nama_status']) ?></span>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
