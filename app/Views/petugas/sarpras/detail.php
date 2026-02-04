<?= $this->extend('petugas/layout') ?>
<?= $this->section('page_title'); ?>Detail Unit: <?= esc($item['kode']) ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('petugas/sarpras') ?>" class="text-decoration-none">Inventaris</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('petugas/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="text-decoration-none"><?= esc($item['nama']) ?></a></li>
            <li class="breadcrumb-item active text-white-50" aria-current="page"><?= esc($item['kode']) ?></li>
        </ol>
    </nav>
    <h1 class="h2">Detail Unit</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('petugas/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Unit
        </a>
    </div>
</div>

<div class="row justify-content-center mt-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-white">
                <div class="text-center mb-4 border-bottom border-secondary pb-3">
                    <h3 class="mb-0 text-white"><?= esc($item['nama']) ?></h3>
                    <code class="fs-5 text-primary"><?= esc($item['kode']) ?></code>
                </div>

                <div class="row g-3">
                    <div class="col-6">
                        <label class="text-white-50 small d-block">Kategori</label>
                        <span class="fw-bold"><?= esc($item['nama_kategori']) ?></span>
                    </div>
                    <div class="col-6">
                        <label class="text-white-50 small d-block">Lokasi</label>
                        <span class="fw-bold"><?= esc($item['nama_lokasi']) ?></span>
                    </div>
                    <div class="col-6">
                        <label class="text-white-50 small d-block">Kondisi</label>
                        <?php
                            $badge = $item['kondisi_id'] == 1 ? 'bg-success' : ($item['kondisi_id'] == 2 ? 'bg-warning text-dark' : 'bg-danger');
                        ?>
                        <span class="badge <?= $badge ?>"><?= esc($item['nama_kondisi']) ?></span>
                    </div>
                    <div class="col-6">
                        <label class="text-white-50 small d-block">Status Unit</label>
                        <span class="badge bg-primary">Tersedia</span>
                    </div>
                </div>

                <div class="timeline mt-4">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <i class="bi bi-plus-circle-fill text-success fs-4"></i>
                        </div>
                        <div class="border-start border-secondary ps-3 pb-3">
                            <small class="text-white-50 d-block mb-1"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></small>
                            <h6 class="mb-1 text-white">Barang Terdaftar</h6>
                            <p class="mb-0 text-white-50 small">Unit baru ditambahkan ke sistem di lokasi <strong><?= esc($item['nama_lokasi']) ?></strong> dengan kondisi <strong><?= esc($item['nama_kondisi']) ?></strong>.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-info-circle-fill text-info fs-4"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-1 text-white">Status Saat Ini</h6>
                            <p class="mb-0 text-white-50 small">Unit tersedia untuk digunakan/dipinjam.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
