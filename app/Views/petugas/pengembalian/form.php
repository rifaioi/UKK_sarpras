<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Proses Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Proses Pengembalian & Inspeksi Kondisi Alat</h1>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-body">
                <h5 class="card-title">Detail Peminjaman</h5>
                <p><strong>Peminjam:</strong> <?= esc($peminjaman['nama_lengkap']) ?></p>
                <p><strong>Barang:</strong> <?= esc($peminjaman['nama_barang']) ?></p>
                <p><strong>Jumlah:</strong> <?= esc($peminjaman['jumlah']) ?></p>
                <p><strong>Tgl Pinjam:</strong> <?= date('d/m/Y', strtotime($peminjaman['tgl_pinjam'])) ?></p>
            </div>
        </div>

        <form action="<?= base_url('petugas/pengembalian/store') ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="peminjaman_id" value="<?= $peminjaman['id'] ?>">
            
            <div class="mb-3">
                <label class="form-label"><strong>Kondisi Barang Saat Kembali *</strong></label>
                <select class="form-select" name="kondisi_id" required>
                    <option value="">-- Pilih Kondisi --</option>
                    <?php foreach($conditions as $cond): ?>
                        <option value="<?= $cond['id'] ?>"><?= $cond['nama_kondisi'] ?></option>
                    <?php endforeach; ?>
                </select>
                <div class="form-text">Pastikan cek kondisi barang dengan teliti.</div>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Laporan Kerusakan / Deskripsi Detail</strong></label>
                <textarea class="form-control" name="deskripsi" rows="4" placeholder="Contoh: Layar retak di bagian kanan bawah, tombol volume tidak berfungsi, body lecet..."></textarea>
                <div class="form-text">Deskripsikan kondisi barang secara detail jika ada kerusakan atau masalah.</div>
            </div>

            <div class="mb-3">
                <label class="form-label"><strong>Foto Barang (Opsional)</strong></label>
                <input type="file" class="form-control" name="foto" accept="image/*">
                <div class="form-text">Unggah foto barang jika ada kerusakan/masalah. Format: JPG, PNG (Max 2MB)</div>
            </div>
            
            <button type="submit" class="btn btn-primary">Simpan Pengembalian</button>
            <a href="<?= base_url('petugas/pengembalian') ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
