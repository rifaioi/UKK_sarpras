<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Perbandingan Kondisi Alat - <?= esc($pengembalian['nama_barang']) ?><?= $this->endSection(); ?>

<?= $this->section('content') ?>
<nav aria-label="breadcrumb">
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= base_url('admin/pengembalian/riwayat') ?>">Riwayat</a></li>
    <li class="breadcrumb-item active" aria-current="page">Detail Perbandingan</li>
  </ol>
</nav>

<div class="row mb-3">
    <div class="col-md-8">
        <h2><i class="bi bi-arrow-left-right me-2 text-primary"></i>Perbandingan Kondisi Alat (Admin)</h2>
        <p class="text-muted">Laporan perbandingan kondisi unit saat keluar vs saat kembali.</p>
    </div>
    <div class="col-md-4 text-end">
        <a href="<?= base_url('admin/pengembalian/riwayat') ?>" class="btn btn-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>

<div class="row">
    <!-- INFO RINGKAS -->
    <div class="col-md-12 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-body bg-light rounded shadow-sm">
                <div class="row align-items-center">
                    <div class="col-md-4 border-end">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Informasi Aset</small>
                        <span class="fw-bold fs-5 text-primary"><?= esc($pengembalian['nama_barang']) ?></span>
                        <div class="small">Peminjam: <strong><?= esc($pengembalian['nama_lengkap']) ?></strong></div>
                    </div>
                    <div class="col-md-4 border-end ps-4">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Timeline Peminjaman</small>
                        <div class="d-flex align-items-center mt-1">
                            <div class="text-center me-3">
                                <small class="d-block text-muted">PINJAM</small>
                                <span class="fw-bold"><?= date('d M Y', strtotime($pengembalian['tgl_pinjam'])) ?></span>
                            </div>
                            <i class="bi bi-arrow-right text-muted fs-4"></i>
                            <div class="text-center ms-3">
                                <small class="d-block text-muted">KEMBALI</small>
                                <span class="fw-bold"><?= date('d M Y', strtotime($pengembalian['tgl_pengembalian'])) ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 ps-4">
                        <small class="text-muted d-block text-uppercase fw-bold" style="font-size: 0.7rem;">Status Akhir di Sistem</small>
                        <?php 
                            $badgeClass = 'bg-secondary';
                            if($pengembalian['nama_kondisi'] == 'Baik') $badgeClass = 'bg-success';
                            elseif($pengembalian['nama_kondisi'] == 'Rusak Ringan') $badgeClass = 'bg-warning text-dark';
                            elseif($pengembalian['nama_kondisi'] == 'Rusak Berat') $badgeClass = 'bg-danger';
                        ?>
                        <span class="badge <?= $badgeClass ?> fs-6 py-2 px-3 mt-1"><?= esc($pengembalian['nama_kondisi']) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- FOTO PERBANDINGAN -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100 border-0 overflow-hidden">
            <div class="card-header bg-primary text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-up me-2"></i>BUKTI SAAT KELUAR</h6>
            </div>
            <div class="card-body text-center d-flex flex-column bg-white">
                <?php if ($inspeksiKeluar && !empty($inspeksiKeluar['photo_evidence'])) : ?>
                    <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-dark rounded mb-3" style="min-height: 300px;">
                        <img src="<?= base_url($inspeksiKeluar['photo_evidence']) ?>" class="img-fluid" style="max-height: 300px;" alt="Awal">
                    </div>
                    <div class="text-start bg-light p-3 rounded border">
                        <h6 class="small fw-bold border-bottom pb-2 mb-2">Catatan Keluar:</h6>
                        <span class="small fst-italic"><?= esc($inspeksiKeluar['notes'] ?: 'Tidak ada catatan') ?></span>
                    </div>
                <?php else : ?>
                    <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center border border-dashed rounded py-5 bg-light">
                        <i class="bi bi-image text-muted display-4 mb-2"></i>
                        <span class="text-muted">Tidak ada foto saat keluar</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100 border-0 overflow-hidden">
            <div class="card-header bg-success text-white py-3">
                <h6 class="mb-0 fw-bold"><i class="bi bi-box-arrow-in-down me-2"></i>BUKTI SAAT KEMBALI</h6>
            </div>
            <div class="card-body text-center d-flex flex-column bg-white">
                <?php if ($inspeksiKembali && !empty($inspeksiKembali['photo_evidence'])) : ?>
                    <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-dark rounded mb-3" style="min-height: 300px;">
                        <img src="<?= base_url($inspeksiKembali['photo_evidence']) ?>" class="img-fluid" style="max-height: 300px;" alt="Akhir">
                    </div>
                    <div class="text-start bg-light p-3 rounded border">
                        <h6 class="small fw-bold border-bottom pb-2 mb-2">Catatan Kembali:</h6>
                        <span class="small fst-italic"><?= esc($inspeksiKembali['notes'] ?: 'Tidak ada catatan') ?></span>
                    </div>
                <?php else : ?>
                    <div class="flex-grow-1 d-flex flex-column align-items-center justify-content-center border border-dashed rounded py-5 bg-light">
                        <i class="bi bi-image text-muted display-4 mb-2"></i>
                        <span class="text-muted">Tidak ada foto saat kembali</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- TABEL CHECKLIST COMPARISON -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-bottom py-3">
                <h6 class="mb-0 fw-bold text-dark"><i class="bi bi-card-checklist me-2 text-primary"></i>Pengecekan Detail Item</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3" style="width: 40%">Nama Bagian / Komponen</th>
                                <th class="text-center py-3" style="width: 30%">Status Saat Keluar</th>
                                <th class="text-center py-3" style="width: 30%">Status Saat Kembali</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($checklistTemplates)) : ?>
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted">
                                        <i class="bi bi-info-circle me-2"></i>Tidak ada template checklist untuk kategori ini.
                                    </td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($checklistTemplates as $item) : 
                                    $resKeluar = $resultsKeluar[$item['id']] ?? null;
                                    $resKembali = $resultsKembali[$item['id']] ?? null;
                                    
                                    $statusOut = $resKeluar['status'] ?? 'N/A';
                                    $statusIn = $resKembali['status'] ?? 'N/A';
                                    
                                    $isChanged = ($statusOut != $statusIn && $statusIn != 'N/A');
                                    $rowClass = $isChanged ? 'table-danger' : '';
                                ?>
                                    <tr class="<?= $rowClass ?>">
                                        <td class="ps-4">
                                            <span class="fw-bold text-dark"><?= esc($item['nama_item']) ?></span>
                                            <?php if ($isChanged): ?>
                                                <div class="badge bg-danger ms-2" style="font-size: 0.6rem;">ADA PERUBAHAN</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= renderStatusBadge($statusOut); ?>
                                            <?php if(!empty($resKeluar['description'])): ?>
                                                <div class="small text-muted mt-1 fst-italic">"<?= esc($resKeluar['description']) ?>"</div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="text-center">
                                            <?= renderStatusBadge($statusIn); ?>
                                            <?php if(!empty($resKembali['description'])): ?>
                                                <div class="small text-muted mt-1 fst-italic">"<?= esc($resKembali['description']) ?>"</div>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
function renderStatusBadge($status) {
    $status = strtolower($status);
    if ($status == 'ok') {
        echo '<span class="badge bg-success border border-success border-opacity-25 px-3 py-2" style="font-weight: 500;"><i class="bi bi-check-circle me-1"></i>BAIK</span>';
    } elseif ($status == 'damaged') {
        echo '<span class="badge bg-warning text-dark border border-warning border-opacity-25 px-3 py-2" style="font-weight: 500;"><i class="bi bi-exclamation-triangle me-1"></i>RUSAK</span>';
    } elseif ($status == 'missing') {
        echo '<span class="badge bg-danger border border-danger border-opacity-25 px-3 py-2" style="font-weight: 500;"><i class="bi bi-x-circle me-1"></i>HILANG</span>';
    } else {
        echo '<span class="badge bg-secondary border border-secondary border-opacity-25 px-3 py-2" style="font-weight: 500;">TIDAK ADA DATA</span>';
    }
}
?>

<style>
.border-dashed { border-style: dashed !important; border-width: 2px !important; }
.card-header { border-bottom: none; }
.table > :not(caption) > * > * { padding: 1rem 0.5rem; }
</style>

<?= $this->endSection() ?>
