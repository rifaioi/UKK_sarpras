<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Proses Pengembalian<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Proses Pengembalian & Pemeriksaan Kondisi Alat</h1>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4 bg-dark text-white">
            <div class="card-header border-0 py-3">
                <h5 class="card-title mb-0"><i class="bi bi-info-circle me-2"></i>Detail Peminjaman Aktif (Admin View)</h5>
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
                <form action="<?= base_url('admin/pengembalian/store') ?>" method="post" enctype="multipart/form-data" id="returnForm" class="form-confirm">
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
                        <label class="form-label fw-bold" id="desc-label">Laporan / Catatan Tambahan</label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" placeholder="Sebutkan jika ada kerusakan kecil, lecet, atau kelengkapan yang hilang..."></textarea>
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
                    
                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1" id="btnSubmit" disabled>
                            <i class="bi bi-save me-2"></i>Simpan Pengembalian
                        </button>
                        <a href="<?= base_url('admin/pengembalian') ?>" class="btn btn-action px-4 d-flex align-items-center">
                            <i class="bi bi-x-circle me-2"></i>Batal
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

    // Enhanced validation for damaged/lost items
    const kondisiSelect = document.querySelector('select[name="kondisi_id"]');
    const descTextarea = document.getElementById('deskripsi');
    const descLabel = document.getElementById('desc-label');
    const fotoInput = document.querySelector('input[name="foto"]');
    const fotoLabel = document.querySelector('label[for="foto"]') || document.querySelectorAll('label.form-label')[2];

    kondisiSelect.addEventListener('change', function() {
        const val = this.value;
        // ID 1 is 'Baik'. If anything else (2: Rusak Ringan, 3: Rusak Berat, 4: Hilang), make fields required.
        if (val != "" && val != "1") {
            // Make deskripsi required with minimum 20 characters
            descTextarea.required = true;
            descTextarea.minLength = 20;
            descLabel.innerHTML = 'Laporan / Catatan Tambahan <span class="text-danger">* (Wajib min 20 karakter)</span>';
            descTextarea.classList.add('border-danger');
            descTextarea.placeholder = "WAJIB: Jelaskan detail kerusakan atau alasan kehilangan barang minimal 20 karakter...";
            
            // Make foto required
            fotoInput.required = true;
            if (fotoLabel) {
                fotoLabel.innerHTML = 'Foto Bukti <span class="text-danger">* (Wajib untuk kondisi rusak/hilang, max 2MB)</span>';
            }
        } else {
            descTextarea.required = false;
            descTextarea.minLength = 0;
            descLabel.innerHTML = 'Laporan / Catatan Tambahan';
            descTextarea.classList.remove('border-danger');
            descTextarea.placeholder = "Sebutkan jika ada kerusakan kecil, lecet, atau kelengkapan yang hilang...";
            
            fotoInput.required = false;
            if (fotoLabel) {
                fotoLabel.innerHTML = 'Foto Bukti (Jika Rusak/Masalah) <span class="text-muted small">(max 2MB)</span>';
            }
        }
    });

    // Validate file size
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            const maxSize = 2 * 1024 * 1024; // 2MB
            
            if (file.size > maxSize) {
                this.classList.add('is-invalid');
                alert('Ukuran file terlalu besar! Maksimal 2MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                this.value = '';
                return;
            }
            
            this.classList.remove('is-invalid');
        }
    });

    // Validate deskripsi length when required
    descTextarea.addEventListener('input', function() {
        if (this.required && this.value.trim().length > 0 && this.value.trim().length < 20) {
            this.classList.add('is-invalid');
            this.setCustomValidity('Deskripsi minimal 20 karakter untuk kondisi rusak/hilang');
        } else {
            this.classList.remove('is-invalid');
            this.setCustomValidity('');
        }
    });

    // Form validation before submit
    const returnForm = document.getElementById('returnForm');
    returnForm.addEventListener('submit', function(e) {
        let isValid = true;
        
        // Check if kondisi is damaged/lost
        const kondisiVal = kondisiSelect.value;
        if (kondisiVal != "" && kondisiVal != "1") {
            // Validate deskripsi
            if (descTextarea.value.trim().length < 20) {
                descTextarea.classList.add('is-invalid');
                isValid = false;
            }
            
            // Validate foto is uploaded
            if (fotoInput.files.length === 0) {
                fotoInput.classList.add('is-invalid');
                alert('Foto bukti wajib diupload untuk kondisi rusak/hilang!');
                isValid = false;
            }
        }
        
        // Validate file size if file is selected
        if (fotoInput.files.length > 0) {
            const file = fotoInput.files[0];
            const maxSize = 2 * 1024 * 1024;
            
            if (file.size > maxSize) {
                fotoInput.classList.add('is-invalid');
                isValid = false;
            }
        }
        
        if (!isValid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    }, true);
</script>
<?= $this->endSection() ?>
