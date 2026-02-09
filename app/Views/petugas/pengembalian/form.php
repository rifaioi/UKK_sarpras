<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Proses Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Proses Pengembalian & Pemeriksaan Kondisi Alat</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4 bg-dark text-white">
            <div class="card-header border-0 py-3">
                <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Detail Peminjaman Aktif</h5>
            </div>
            <div class="card-body p-4">
                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <div class="bg-secondary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="bi bi-person-badge text-primary fs-1"></i>
                        </div>
                    </div>
                    <div class="col">
                        <h4 class="mb-0 text-white"><?= esc($peminjaman['nama_lengkap']) ?></h4>
                        <span class="badge bg-info text-dark">Peminjam Aktif</span>
                        <div class="mt-2 text-white-50 small">
                            <i class="bi bi-upc-scan me-1"></i> ID Peminjaman: #<?= $peminjaman['id'] ?> | 
                            <i class="bi bi-calendar-event me-1"></i> <?= date('d M Y', strtotime($peminjaman['tgl_pinjam'])) ?>
                        </div>
                    </div>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="p-3 border border-secondary rounded bg-dark bg-opacity-50">
                            <label class="text-white-50 d-block small mb-1">Barang / Alat</label>
                            <h5 class="mb-0 text-white"><?= esc($peminjaman['nama_barang']) ?></h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border border-secondary rounded bg-dark bg-opacity-50 text-center">
                            <label class="text-white-50 d-block small mb-1">Jumlah</label>
                            <h5 class="mb-0 text-white"><?= esc($peminjaman['jumlah']) ?> Unit</h5>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3 border border-secondary rounded bg-dark bg-opacity-50 text-center">
                            <label class="text-white-50 d-block small mb-1">Estimasi Durasi</label>
                            <?php 
                                $start = new DateTime($peminjaman['tgl_pinjam']);
                                $end = new DateTime($peminjaman['tgl_kembali_rencana']);
                                $diff = $start->diff($end)->days;
                            ?>
                            <h5 class="mb-0 text-white"><?= $diff > 0 ? $diff : 1 ?> Hari</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm border-start border-4 border-warning">
            <div class="card-body p-4">
                <form action="<?= base_url('petugas/pengembalian/store') ?>" method="post" enctype="multipart/form-data" id="returnForm">
                    <input type="hidden" name="peminjaman_id" value="<?= $peminjaman['id'] ?>">
                    
                    <h5 class="section-title mb-4 border-bottom pb-2">Pemeriksaan Pengembalian</h5>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Kondisi Barang Saat Kembali *</label>
                        <select class="form-select" name="kondisi_id" required>
                            <option value="">-- Pilih Kondisi Visual --</option>
                            <?php foreach($conditions as $cond): ?>
                                <option value="<?= $cond['id'] ?>"><?= esc($cond['nama_kondisi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text text-muted"><i class="bi bi-shield-check"></i> Pilih kondisi fisik barang setelah diperiksa secara langsung.</div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Laporan / Catatan Tambahan</label>
                        <textarea class="form-control" name="deskripsi" rows="3" placeholder="Sebutkan jika ada kerusakan kecil, lecet, atau kelengkapan yang hilang..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Foto Bukti (Jika Rusak/Masalah)</label>
                        <input type="file" class="form-control" name="foto" accept="image/*">
                        <div class="form-text small">Unggah foto dokumentasi jika barang dikembalikan dalam kondisi tidak baik.</div>
                    </div>

                    <div class="mb-4 p-3 border border-warning border-opacity-25 rounded" style="background: rgba(255, 193, 7, 0.05);">
                        <div class="form-check">
                            <input class="form-check-input border-warning" type="checkbox" value="" id="confirmReturn" required>
                            <label class="form-check-label fw-bold text-white-50" for="confirmReturn">
                                Saya sudah memeriksa kondisi fisik barang dan data di atas benar.
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary px-4 py-2 flex-grow-1" id="btnSubmit" disabled>
                            <i class="bi bi-check-circle-fill me-2"></i> Simpan Pengembalian
                        </button>
                        <a href="<?= base_url('petugas/pengembalian') ?>" class="btn btn-action px-4 d-flex align-items-center">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('confirmReturn').addEventListener('change', function() {
        document.getElementById('btnSubmit').disabled = !this.checked;
    });
</script>
<?= $this->endSection() ?>
