<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Jadwal Maintenance<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Jadwal Maintenance</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary px-3" data-bs-toggle="modal" data-bs-target="#addScheduleModal">
            <i class="bi bi-plus-lg me-1"></i> Tambah Jadwal
        </button>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?= base_url('admin/maintenance/schedules') ?>" method="get" class="row g-3">
            <div class="col-md-5">
                <div class="input-group input-group-sm">
                    <span class="input-group-text bg-transparent border-end-0"><i class="bi bi-search"></i></span>
                    <input type="text" name="q" class="form-control border-start-0 shadow-none" placeholder="Cari aset, teknisi..." value="<?= esc($filter_q) ?>">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm shadow-none">
                    <option value="">Semua Status</option>
                    <option value="Scheduled" <?= $filter_status == 'Scheduled' ? 'selected' : '' ?>>Scheduled</option>
                    <option value="Delayed" <?= $filter_status == 'Delayed' ? 'selected' : '' ?>>Delayed</option>
                    <option value="Canceled" <?= $filter_status == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                    <option value="Completed" <?= $filter_status == 'Completed' ? 'selected' : '' ?>>Completed</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-sm btn-outline-secondary px-3 shadow-none">Filter</button>
                <a href="<?= base_url('admin/maintenance/schedules') ?>" class="btn btn-sm btn-link text-decoration-none text-muted">Reset</a>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-3" style="width: 50px;">
                            <div class="form-check"><input class="form-check-input" type="checkbox"></div>
                        </th>
                        <th>TANGGAL</th>
                        <th>ASET</th>
                        <th>TIPE</th>
                        <th>TEKNISI</th>
                        <th>STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($schedules)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada jadwal maintenance.</td></tr>
                    <?php else: ?>
                        <?php foreach($schedules as $s): ?>
                        <tr>
                            <td class="ps-3"><div class="form-check"><input class="form-check-input" type="checkbox"></div></td>
                            <td>
                                <div class="fw-bold"><?= date('d M Y', strtotime($s['scheduled_date'])) ?></div>
                            </td>
                            <td>
                                <div class="fw-bold fs-6"><?= esc($s['asset_name']) ?></div>
                                <div class="text-muted small"><?= esc($s['asset_kode']) ?></div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-clipboard-check me-2 text-primary"></i>
                                    <div>
                                        <div class="small fw-bold"><?= esc($s['maintenance_type']) ?></div>
                                        <div class="text-muted smallest"><?= esc($s['action_type']) ?></div>
                                    </div>
                                </div>
                            </td>
                            <td><?= esc($s['technician']) ?></td>
                            <td>
                                <?php 
                                    $statusClass = 'bg-secondary';
                                    if($s['status'] == 'Scheduled') $statusClass = 'bg-info';
                                    elseif($s['status'] == 'Completed') $statusClass = 'bg-success';
                                    elseif($s['status'] == 'Canceled') $statusClass = 'bg-danger';
                                    elseif($s['status'] == 'Delayed') $statusClass = 'bg-warning text-dark';
                                ?>
                                <span class="badge rounded-pill <?= $statusClass ?> fs-smallest">
                                    <i class="bi bi-circle-fill me-1 smallest"></i> <?= $s['status'] ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <?php if($s['status'] == 'Scheduled'): ?>
                                    <button class="btn btn-sm btn-success px-3 btn-complete" 
                                        data-id="<?= $s['id'] ?>" 
                                        data-asset="<?= esc($s['asset_name']) ?>"
                                        data-bs-toggle="modal" data-bs-target="#completeModal">
                                        Selesaikan
                                    </button>
                                <?php endif; ?>
                                <a href="<?= base_url('admin/maintenance/schedules/delete/'.$s['id']) ?>" class="btn btn-sm btn-icon text-danger btn-delete" title="Batalkan">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Jadwal -->
<div class="modal fade" id="addScheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kelola Jadwal Maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/maintenance/schedules/store') ?>" method="post" class="form-confirm">
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="form-label fw-bold small text-muted mb-0">ASET (SARPRAS)</label>
                            <div class="btn-group btn-group-sm" role="group">
                                <input type="radio" class="btn-check" name="selection_mode" id="modeSatuan" value="satuan" checked autocomplete="off">
                                <label class="btn btn-outline-primary" for="modeSatuan">Satuan</label>

                                <input type="radio" class="btn-check" name="selection_mode" id="modeKategori" value="kategori" autocomplete="off">
                                <label class="btn btn-outline-primary" for="modeKategori">Kategori</label>
                            </div>
                        </div>

                        <!-- Mode Satuan Select -->
                        <div id="selectSatuan">
                            <select name="sarpras_ids[]" class="form-select select2" multiple style="width: 100%;">
                                <?php foreach($assets as $asset): ?>
                                    <option value="<?= $asset['id'] ?>"><?= esc($asset['nama']) ?> (<?= $asset['kode'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text smallest text-muted mt-1">Pilih satu atau lebih aset secara spesifik.</div>
                        </div>

                        <!-- Mode Kategori Select -->
                        <div id="selectKategori" class="d-none">
                            <select name="kategori_ids[]" class="form-select select2" multiple style="width: 100%;">
                                <?php foreach($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>"><?= esc($cat['nama']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <div class="form-text smallest text-muted mt-1">Seluruh aset dalam kategori terpilih akan dijadwalkan.</div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">TANGGAL MAINTENANCE</label>
                            <input type="date" name="scheduled_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">TIPE</label>
                            <select name="maintenance_type" class="form-select">
                                <option value="Rutin">Rutin</option>
                                <option value="Perbaikan">Perbaikan</option>
                                <option value="Darurat">Darurat</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">JENIS TINDAKAN</label>
                        <select name="action_type" class="form-select">
                            <option value="Pembersihan">Pembersihan</option>
                            <option value="Pengecekan">Pengecekan</option>
                            <option value="Perbaikan">Perbaikan</option>
                            <option value="Penggantian Suku Cadang">Penggantian Suku Cadang</option>
                            <option value="Kalibrasi">Kalibrasi</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">TEKNISI</label>
                        <input type="text" name="technician" class="form-control" placeholder="Nama teknisi / vendor" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">DESKRIPSI (OPSIONAL)</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Detail rencana pengerjaan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary px-4">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Selesaikan Maintenance -->
<div class="modal fade" id="completeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Selesaikan Maintenance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/maintenance/records/store') ?>" method="post" class="form-confirm">
                <input type="hidden" name="schedule_id" id="complete_schedule_id">
                <div class="modal-body">
                    <p class="small text-muted mb-3">Update status pengerjaan untuk aset <span id="complete_asset_name" class="fw-bold text-primary"></span>.</p>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">TANGGAL SELESAI</label>
                            <input type="date" name="completion_date" class="form-control" required value="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">HASIL MAINTENANCE</label>
                            <select name="result" class="form-select">
                                <option value="Baik">Baik</option>
                                <option value="Selesai dengan Catatan">Selesai dengan Catatan</option>
                                <option value="Butuh Tindak Lanjut">Butuh Tindak Lanjut</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">KONDISI ASET SESUDAH</label>
                            <select name="condition_after" class="form-select">
                                <option value="1">Sangat Baik</option>
                                <option value="2">Baik</option>
                                <option value="3">Rusak Ringan</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small text-muted">BIAYA</label>
                            <div class="input-group">
                                <span class="input-group-text smallest">Rp</span>
                                <input type="number" name="cost" class="form-control" value="0">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted">CATATAN / DESKRIPSI HASIL</label>
                        <textarea name="notes" class="form-control" rows="3" placeholder="Jelaskan detail pengerjaan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .smallest { font-size: 0.75rem; }
    .fs-smallest { font-size: 0.65rem; }
    .btn-icon { padding: 0.25rem 0.5rem; }
    .table thead th { border-top: none; font-size: 0.7rem; color: #6c757d; font-weight: 600; letter-spacing: 0.05em; }
    .badge { padding: 0.4em 0.8em; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const completeButtons = document.querySelectorAll('.btn-complete');
    const scheduleIdInput = document.getElementById('complete_schedule_id');
    const assetNameSpan = document.getElementById('complete_asset_name');

    completeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            scheduleIdInput.value = this.getAttribute('data-id');
            assetNameSpan.textContent = this.getAttribute('data-asset');
        });
    });

    // Toggle Selection Mode
    const modeSatuan = document.getElementById('modeSatuan');
    const modeKategori = document.getElementById('modeKategori');
    const selectSatuan = document.getElementById('selectSatuan');
    const selectKategori = document.getElementById('selectKategori');

    function updateSelectionMode() {
        if (modeSatuan.checked) {
            selectSatuan.classList.remove('d-none');
            selectKategori.classList.add('d-none');
        } else {
            selectSatuan.classList.add('d-none');
            selectKategori.classList.remove('d-none');
        }
    }

    modeSatuan.addEventListener('change', updateSelectionMode);
    modeKategori.addEventListener('change', updateSelectionMode);
});
</script>

<?= $this->endSection() ?>
