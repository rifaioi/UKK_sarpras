<?= $this->extend($role . '/layout') ?>

<?= $this->section('page_title') ?>
Pemeriksaan Barang: <?= esc($peminjaman['barang']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card shadow">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clipboard-check me-2"></i>Form Inspeksi (<?= $type == 'keluar' ? 'Barang Keluar' : 'Barang Kembali' ?>)
                </h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <strong>Peminjam:</strong> <?= esc($peminjaman['peminjam']) ?><br>
                    <strong>Barang:</strong> <?= esc($peminjaman['barang']) ?> (<?= esc($peminjaman['kode_barang']) ?>)
                </div>

                <!-- Photo Comparison moved to bottom -->

                <form action="<?= base_url($role . '/inspections/store') ?>" method="post" enctype="multipart/form-data" class="form-confirm">
                    <?= csrf_field() ?>
                    <input type="hidden" name="peminjaman_id" value="<?= $peminjaman['id'] ?>">
                    <input type="hidden" name="type" value="<?= $type ?>">

                    <h6 class="border-bottom pb-2 mb-3">Checklist Kondisi</h6>
                    
                    <?php if (empty($checklistItems)) : ?>
                        <p class="text-warning">Tidak ada template checklist untuk kategori ini. Mohon lakukan pengecekan manual dan catat di notes.</p>
                    <?php else : ?>
                        <div class="table-responsive mb-3">
                            <table class="table table-striped table-sm text-white">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Bagian / Item</th>
                                        <?php if ($type == 'kembali') : ?>
                                            <th class="table-info text-dark" style="width: 20%;">Kondisi Keluar</th>
                                        <?php endif; ?>
                                        <th style="width: 25%;">Kondisi Saat Ini</th>
                                        <th>Keterangan (Opsional)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($checklistItems as $item) : 
                                        $preStatus = 'ok'; // Default for handover or missing data
                                    ?>
                                        <tr>
                                            <td><?= esc($item['nama_item']) ?></td>
                                            
                                            <?php if ($type == 'kembali') : 
                                                $preItem = $preBorrowResults[$item['id']] ?? null;
                                                $preStatus = $preItem['status'] ?? 'ok';
                                                
                                                $bgClass = 'table-info text-dark'; // Default comparison bg
                                                $icon = '';
                                                
                                                if ($preStatus == 'ok') $icon = '<i class="bi bi-check-circle-fill text-success"></i> ';
                                                if ($preStatus == 'damaged') $icon = '<i class="bi bi-exclamation-circle-fill text-danger"></i> ';
                                                if ($preStatus == 'missing') $icon = '<i class="bi bi-x-circle-fill text-danger"></i> ';
                                            ?>
                                                <td class="<?= $bgClass ?>">
                                                    <?= $icon . strtoupper($preStatus) ?>
                                                    <?php if(!empty($preItem['description'])): ?>
                                                        <div class="small fst-italic text-muted">(<?= esc($preItem['description']) ?>)</div>
                                                    <?php endif; ?>
                                                </td>
                                            <?php endif; ?>

                                            <td>
                                                <select name="results[<?= $item['id'] ?>]" 
                                                        class="form-select form-select-sm status-select" 
                                                        data-pre-status="<?= strtolower($preStatus) ?>"
                                                        required>
                                                    <option value="ok" <?= ($preStatus == 'ok') ? 'selected' : '' ?>>OK / Baik</option>
                                                    <option value="damaged">Rusak / Cacat</option>
                                                    <option value="missing">Hilang</option>
                                                    <option value="n/a">Tidak Ada / N/A</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="descriptions[<?= $item['id'] ?>]" class="form-control form-control-sm" placeholder="Detail kerusakan...">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>

                    <!-- Bukti Foto Section -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Bukti Foto (Wajib)</label>
                        
                        <?php if ($type == 'kembali') : ?>
                            <!-- Comparison Layout for Returns -->
                            <div class="card bg-dark bg-opacity-25 border-secondary">
                                <div class="card-body">
                                    <div class="row text-center">
                                        <!-- Before Photo -->
                                        <div class="col-md-6 border-end border-secondary">
                                            <h6 class="text-muted mb-3 small text-uppercase">Kondisi Awal (Saat Keluar)</h6>
                                            <?php if (isset($preInspection) && !empty($preInspection['photo_evidence'])) : ?>
                                                <img src="<?= base_url($preInspection['photo_evidence']) ?>" class="img-fluid rounded shadow-sm mb-2" style="max-height: 250px; object-fit: cover;">
                                                <div class="small text-muted fst-italic">
                                                    Catatan: "<?= esc($preInspection['notes'] ?? '-') ?>"
                                                </div>
                                            <?php else : ?>
                                                <div class="d-flex align-items-center justify-content-center bg-secondary bg-opacity-10 rounded" style="height: 200px;">
                                                    <span class="text-muted small fst-italic">Foto awal tidak tersedia</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <!-- After Photo (Upload) -->
                                        <div class="col-md-6">
                                            <h6 class="text-primary mb-3 small text-uppercase">Kondisi Sekarang (Baru)</h6>
                                            
                                            <div class="mb-3">
                                                <input type="file" class="form-control form-control-sm" name="photo" id="photo" accept="image/*" required onchange="previewImage(this)">
                                            </div>

                                            <!-- Preview Container -->
                                            <div class="d-flex align-items-center justify-content-center bg-dark bg-opacity-50 rounded border border-secondary border-dashed" style="height: 200px; min-height: 200px;">
                                                <img id="photoPreview" src="#" alt="Preview" class="img-fluid rounded d-none" style="max-height: 100%; max-width: 100%;">
                                                <div id="previewPlaceholder" class="text-muted small">
                                                    <i class="bi bi-camera fs-2 d-block mb-1"></i>
                                                    Preview foto akan muncul di sini
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php else : ?>
                            <!-- Standard Upload for Check-out -->
                            <input type="file" class="form-control" name="photo" id="photo" accept="image/*" required>
                            <div class="form-text">Upload foto kondisi barang saat ini sebagai bukti serah terima.</div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                            <i class="bi bi-save me-2"></i>Simpan Inspeksi <?= $type == 'keluar' ? 'Keluar' : 'Kembali' ?>
                        </button>
                        <a href="<?= base_url($role . '/peminjaman') ?>" class="btn btn-action px-4 d-flex align-items-center">
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
    const selects = document.querySelectorAll('.status-select');
    
    function checkChanges(select) {
        const row = select.closest('tr');
        const preStatus = select.getAttribute('data-pre-status');
        const currentStatus = select.value;
        const isReturn = <?= ($type == 'kembali') ? 'true' : 'false' ?>;

        if (isReturn && preStatus && preStatus !== 'n/a' && preStatus !== '-') {
            if (currentStatus !== preStatus) {
                row.classList.add('table-warning');
                row.style.borderLeft = '4px solid #ffc107';
                
                // Add indicator if not present
                if (!row.querySelector('.change-badge')) {
                    const cell = row.cells[0];
                    const badge = document.createElement('span');
                    badge.className = 'badge bg-warning text-dark ms-2 change-badge';
                    badge.innerHTML = '<i class="bi bi-exclamation-triangle"></i> BERUBAH';
                    cell.appendChild(badge);
                }
            } else {
                row.classList.remove('table-warning');
                row.style.borderLeft = 'none';
                const badge = row.querySelector('.change-badge');
                if (badge) badge.remove();
            }
        }
    }

    selects.forEach(select => {
        // Initial check
        checkChanges(select);
        
        // Listen for changes
        select.addEventListener('change', function() {
            checkChanges(this);
        });
    });
});

function previewImage(input) {
    const preview = document.getElementById('photoPreview');
    const placeholder = document.getElementById('previewPlaceholder');
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('d-none');
            placeholder.classList.add('d-none');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        preview.src = '#';
        preview.classList.add('d-none');
        placeholder.classList.remove('d-none');
    }
}
</script>
<?= $this->endSection() ?>
