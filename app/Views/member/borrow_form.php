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
                <form action="<?= base_url('member/store_borrow') ?>" method="post">
                    <input type="hidden" name="sarpras_id" value="<?= $item['id'] ?>">
                    
                    <div class="mb-3">
                        <label>Nama Barang</label>
                        <input type="text" class="form-control" value="<?= esc($item['nama']) ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label>Jumlah Pinjam</label>
                        <input type="number" name="jumlah" class="form-control" min="1" max="<?= $item['stok'] ?>" required>
                        <small class="text-muted">Stok tersedia saat ini: <?= $item['stok'] ?></small>
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
                    <button type="submit" class="btn btn-success w-100">Ajukan Peminjaman</button>
                    <a href="<?= base_url('member/dashboard') ?>" class="btn btn-secondary w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
