<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Form Peminjaman<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Form Peminjaman</div>
            <div class="card-body">
                <h5><?= esc($item['nama']) ?></h5>
                <!-- Removed original h5 and p tags as they are replaced by the new input field -->
                <hr>
                <form action="<?= base_url('member/store_borrow') ?>" method="post" class="form-confirm">
                    <input type="hidden" name="sarpras_id" value="<?= $item['id'] ?>">
                    
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" value="<?= esc($item['nama']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Pinjam</label>
                        <input type="number" name="jumlah" class="form-control" min="1" max="<?= $item['stok'] ?>" value="1" required>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted">Stok tersedia: <?= $item['stok'] ?> Unit</small>
                            <small class="text-info"><i class="bi bi-info-circle"></i> Bisa pinjam lebih dari 1 unit</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label><strong>Tujuan Peminjaman</strong></label>
                        <textarea name="tujuan" class="form-control" rows="3" required placeholder="Jelaskan untuk apa barang ini akan digunakan..."></textarea>
                        <small class="text-muted">Contoh: Untuk kegiatan praktikum, acara sekolah, dll.</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Tanggal Mulai Pinjam</label>
                            <input type="date" name="tgl_pinjam" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Estimasi Kembali</label>
                            <input type="date" name="tgl_kembali_rencana" class="form-control" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                            <i class="bi bi-send me-2"></i>Ajukan Peminjaman
                        </button>
                        <a href="<?= base_url('member/dashboard') ?>" class="btn btn-action px-4 d-flex align-items-center">
                            <i class="bi bi-x-circle me-2"></i>Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
