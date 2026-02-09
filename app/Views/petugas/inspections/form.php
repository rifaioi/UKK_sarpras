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

                <?php if ($type == 'kembali' && isset($preInspection) && !empty($preInspection['photo_evidence'])) : ?>
                    <div class="card mb-4 border-warning">
                        <div class="card-header bg-warning text-dark">
                            <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Kondisi Awal (Saat Keluar)</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <img src="<?= base_url($preInspection['photo_evidence']) ?>" class="img-fluid rounded shadow-sm" alt="Kondisi Awal">
                                    <div class="small text-muted mt-1 text-center">Foto diambil: <?= $preInspection['inspection_date'] ?></div>
                                </div>
                                <div class="col-md-8">
                                    <p class="mb-1"><strong>Catatan Awal:</strong></p>
                                    <p class="fst-italic bg-light p-2 rounded text-dark"><?= esc($preInspection['notes'] ?? '-') ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url($role . '/inspections/store') ?>" method="post" enctype="multipart/form-data">
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
                                    <?php foreach ($checklistItems as $item) : ?>
                                        <tr>
                                            <td><?= esc($item['nama_item']) ?></td>
                                            
                                            <?php if ($type == 'kembali') : 
                                                $preItem = $preBorrowResults[$item['id']] ?? null;
                                                $preStatus = $preItem['status'] ?? '-';
                                                
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
                                                <select name="results[<?= $item['id'] ?>]" class="form-select form-select-sm" required>
                                                    <option value="ok">OK / Baik</option>
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

                    <div class="mb-3">
                        <label for="photo" class="form-label fw-bold">Bukti Foto (Wajib)</label>
                        <input type="file" class="form-control" name="photo" id="photo" accept="image/*" required>
                        <div class="form-text">Upload foto kondisi barang saat ini.</div>
                    </div>

                    <div class="mb-3">
                        <label for="notes" class="form-label fw-bold">Catatan Tambahan</label>
                        <textarea class="form-control" name="notes" id="notes" rows="3"></textarea>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg py-3">
                            <i class="bi bi-save me-2"></i> Simpan Inspeksi <?= $type == 'keluar' ? 'Keluar' : 'Kembali' ?>
                        </button>
                        <a href="<?= base_url($role . '/peminjaman') ?>" class="btn btn-action py-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
