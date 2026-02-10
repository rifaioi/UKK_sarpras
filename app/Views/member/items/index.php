<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Katalog Barang<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="row pt-4 mb-4">
    <div class="col-12 d-flex justify-content-between align-items-center">
        <h4 class="fw-bold m-0 text-white">Pilih Barang untuk Dipinjam</h4>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-6 mx-auto">
        <form action="" method="get" class="d-flex">
            <input type="text" name="q" class="form-control me-2 bg-dark text-white border-secondary" placeholder="Cari nama barang..." value="<?= esc($q ?? '') ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q): ?>
                <a href="<?= base_url('member/items') ?>" class="btn btn-outline-secondary ms-2 text-white">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="row g-4">
    <?php if (empty($items)): ?>
        <div class="col-12 text-center py-5">
            <i class="bi bi-search fs-1 d-block mb-3 opacity-25"></i>
            <p class="text-secondary">Maaf, saat ini tidak ada barang yang tersedia untuk dipinjam.</p>
        </div>
    <?php endif; ?>

    <?php foreach ($items as $item): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 border-0 shadow-sm bg-dark bg-opacity-25 hover-lift transition-all">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill small">
                            <?= esc($item['nama_kategori']) ?>
                        </span>
                        <span class="text-secondary small font-monospace">Tersedia</span>
                    </div>
                    
                    <h5 class="fw-bold text-white mb-2"><?= esc($item['nama']) ?></h5>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center text-secondary small mb-2">
                            <i class="bi bi-geo-alt me-2"></i>
                            <?= esc($item['nama_lokasi']) ?>
                        </div>
                        <div class="d-flex align-items-center text-secondary small mb-3">
                            <i class="bi bi-box-seam me-2"></i>
                             Total Stok: <strong class="ms-1 text-info"><?= esc($item['tersedia']) ?> / <?= esc($item['total_unit']) ?></strong>
                        </div>
                        
                        <div class="p-2 rounded bg-white bg-opacity-5 border border-white border-opacity-10">
                            <div class="d-flex justify-content-between small mb-1">
                                <span class="text-secondary">Kondisi Baik:</span>
                                <span class="text-success fw-bold"><?= esc($item['jumlah_baik']) ?> Unit</span>
                            </div>
                            <div class="d-flex justify-content-between small">
                                <span class="text-secondary">Rusak Ringan:</span>
                                <span class="<?= $item['jumlah_rusak_ringan'] > 0 ? 'text-warning' : 'text-secondary' ?> fw-bold"><?= esc($item['jumlah_rusak_ringan']) ?> Unit</span>
                            </div>
                        </div>
                    </div>
                    
                    <a href="<?= base_url('member/borrow/'.$item['id']) ?>" class="btn btn-primary w-100 rounded-pill">
                        <i class="bi bi-plus-circle me-2"></i> Pinjam Sekarang
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<style>
.hover-lift { transition: transform 0.2s, background-color 0.2s; }
.hover-lift:hover { transform: translateY(-5px); background-color: rgba(255,255,255,0.05) !important; }
</style>

<?= $this->endSection() ?>
