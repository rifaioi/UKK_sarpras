<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Lapor Kerusakan<?= $this->endSection(); ?>

<?= $this->section('content') ?>



<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">Buat Pengaduan</div>
            <div class="card-body">
                <form action="<?= base_url('member/pengaduan/store') ?>" method="post" enctype="multipart/form-data" id="pengaduanForm" class="form-confirm">
                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text" class="form-control" name="judul" id="judul" required placeholder="Contoh: AC Bocor" minlength="3">
                        <div class="invalid-feedback">Judul minimal 3 karakter.</div>
                    </div>
                    
                    <div class="mb-3">
                         <label class="form-label">Jenis Sarpras (Opsional)</label>
                         <select class="form-select" name="sarpras_id">
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($items as $item): ?>
                                <option value="<?= $item['id'] ?>"><?= esc($item['nama']) ?> (<?= esc($item['kode']) ?>)</option>
                            <?php endforeach; ?>
                         </select>
                    </div>
                    
                    <div class="mb-3">
                         <label class="form-label">Lokasi</label>
                         <select class="form-select" name="lokasi" required>
                            <option value="">-- Pilih Lokasi --</option>
                            <?php foreach($locations as $loc): ?>
                                <option value="<?= esc($loc['nama_lokasi']) ?>"><?= esc($loc['nama_lokasi']) ?></option>
                            <?php endforeach; ?>
                         </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi <span class="text-muted small">(minimal 10 karakter)</span></label>
                        <textarea class="form-control" name="deskripsi" id="deskripsi" rows="3" required minlength="10" placeholder="Jelaskan detail masalah yang Anda temukan..."></textarea>
                        <div class="invalid-feedback">Deskripsi minimal 10 karakter.</div>
                        <small class="text-muted"><span id="charCount">0</span>/10 karakter</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Foto (Opsional) <span class="text-muted small">(max 2MB)</span></label>
                        <input type="file" class="form-control" name="bukti_foto" id="bukti_foto" accept="image/*">
                        <div class="invalid-feedback">Ukuran file maksimal 2MB.</div>
                        <div id="photoPreview" class="mt-2 d-none">
                            <img id="previewImg" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                            <button type="button" class="btn btn-sm btn-danger ms-2" id="removePhoto">
                                <i class="bi bi-x"></i> Hapus
                            </button>
                        </div>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                            <i class="bi bi-send me-2"></i>Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('pengaduanForm');
    const deskripsi = document.getElementById('deskripsi');
    const charCount = document.getElementById('charCount');
    const buktiFile = document.getElementById('bukti_foto');
    const photoPreview = document.getElementById('photoPreview');
    const previewImg = document.getElementById('previewImg');
    const removePhotoBtn = document.getElementById('removePhoto');

    // Character counter for deskripsi
    deskripsi.addEventListener('input', function() {
        const length = this.value.length;
        charCount.textContent = length;
        
        if (length < 10 && length > 0) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // File upload validation and preview
    buktiFile.addEventListener('change', function(e) {
        const file = e.target.files[0];
        
        if (file) {
            // Validate file size (2MB = 2048KB = 2097152 bytes)
            const maxSize = 2 * 1024 * 1024; // 2MB in bytes
            
            if (file.size > maxSize) {
                this.classList.add('is-invalid');
                photoPreview.classList.add('d-none');
                alert('Ukuran file terlalu besar! Maksimal 2MB. File Anda: ' + (file.size / 1024 / 1024).toFixed(2) + 'MB');
                this.value = '';
                return;
            }
            
            this.classList.remove('is-invalid');
            
            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                photoPreview.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Remove photo
    removePhotoBtn.addEventListener('click', function() {
        buktiFile.value = '';
        photoPreview.classList.add('d-none');
        previewImg.src = '';
    });

    // Form validation before submit
    form.addEventListener('submit', function(e) {
        let isValid = true;

        // Validate deskripsi length
        if (deskripsi.value.trim().length < 10) {
            deskripsi.classList.add('is-invalid');
            isValid = false;
        }

        // Validate file size if file is selected
        if (buktiFile.files.length > 0) {
            const file = buktiFile.files[0];
            const maxSize = 2 * 1024 * 1024;
            
            if (file.size > maxSize) {
                buktiFile.classList.add('is-invalid');
                isValid = false;
            }
        }

        if (!isValid) {
            e.preventDefault();
            e.stopImmediatePropagation();
            return false;
        }
    }, true);
});
</script>
<?= $this->endSection() ?>
