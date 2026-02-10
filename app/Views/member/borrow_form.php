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
                            <input type="date" name="tgl_pinjam" id="tgl_pinjam" class="form-control" required min="<?= date('Y-m-d') ?>">
                            <small class="text-info"><i class="bi bi-info-circle"></i> Hanya hari Senin - Jumat</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Estimasi Kembali</label>
                            <input type="date" name="tgl_kembali_rencana" id="tgl_kembali_rencana" class="form-control" required min="<?= date('Y-m-d') ?>">
                            <small class="text-info"><i class="bi bi-info-circle"></i> Maksimal 7 hari</small>
                        </div>
                    </div>
                    
                    <div id="date-warning" class="alert alert-warning py-2 mb-0 d-none" style="font-size: 0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i><span id="warning-text"></span>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tglPinjam = document.getElementById('tgl_pinjam');
    const tglKembali = document.getElementById('tgl_kembali_rencana');
    const warningDiv = document.getElementById('date-warning');
    const warningText = document.getElementById('warning-text');
    const submitBtn = document.querySelector('button[type="submit"]');

    function validateDates() {
        submitBtn.disabled = false;
        warningDiv.classList.add('d-none');

        const pinjamVal = tglPinjam.value;
        const kembaliVal = tglKembali.value;

        if (!pinjamVal) return;

        // Check weekend
        const date = new Date(pinjamVal);
        const day = date.getUTCDay(); // 0 is Sunday, 6 is Saturday
        if (day === 0 || day === 6) {
            warningText.innerText = 'Peminjaman tidak diperbolehkan pada hari Sabtu atau Minggu.';
            warningDiv.classList.remove('d-none');
            submitBtn.disabled = true;
            return;
        }

        if (!kembaliVal) return;

        // Check duration
        const start = new Date(pinjamVal);
        const end = new Date(kembaliVal);
        const diffInMs = end - start;
        const diffDays = Math.floor(diffInMs / (1000 * 60 * 60 * 24));

        if (diffInMs < 0) {
            warningText.innerText = 'Tanggal kembali tidak boleh lebih kecil dari tanggal pinjam.';
            warningDiv.classList.remove('d-none');
            submitBtn.disabled = true;
        } else if (diffDays > 7) {
            warningText.innerText = 'Lama peminjaman maksimal adalah 7 hari (Anda memilih ' + diffDays + ' hari).';
            warningDiv.classList.remove('d-none');
            submitBtn.disabled = true;
        }
    }

    tglPinjam.addEventListener('change', validateDates);
    tglKembali.addEventListener('change', validateDates);
});
</script>

<?= $this->endSection() ?>
