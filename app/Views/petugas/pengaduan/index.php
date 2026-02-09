<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Manajemen Pengaduan<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Pengaduan</h1>
</div>
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form action="<?= base_url('petugas/pengaduan') ?>" method="get" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Filter Status</label>
                <select name="status" class="form-select form-select-sm shadow-none">
                    <option value="">-- Semua Status --</option>
                    <?php foreach($statuses as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= (isset($filterStatus) && $filterStatus == $s['id']) ? 'selected' : '' ?>>
                            <?= esc($s['nama_status']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted">Filter Lokasi</label>
                <select name="lokasi" class="form-select form-select-sm shadow-none">
                    <option value="">-- Semua Lokasi --</option>
                    <?php foreach($locations as $l): ?>
                        <option value="<?= esc($l['nama_lokasi']) ?>" <?= (isset($filterLokasi) && $filterLokasi == $l['nama_lokasi']) ? 'selected' : '' ?>>
                            <?= esc($l['nama_lokasi']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4 d-flex align-items-end gap-2">
                <button type="submit" class="btn btn-sm btn-primary px-3 shadow-none">
                    <i class="bi bi-funnel"></i> Filter
                </button>
                <a href="<?= base_url('petugas/pengaduan') ?>" class="btn btn-sm btn-outline-secondary px-3 shadow-none">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>



<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Pelapor</th>
                        <th>Judul & Lokasi</th>
                        <th>Bukti</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($pengaduan as $i => $p): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        <td><?= esc($p['nama_lengkap']) ?></td>
                        <td>
                            <strong><?= esc($p['judul']) ?></strong><br>
                            <small>(<?= esc($p['lokasi']) ?>)</small>
                        </td>
                        <td>
                            <?php if(!empty($p['bukti_foto'])): ?>
                                <a href="<?= base_url('uploads/pengaduan/' . $p['bukti_foto']) ?>" target="_blank" class="btn btn-sm btn-info">
                                    Lihat
                                </a>
                            <?php else: ?>
                                -
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php 
                                $badgeClass = 'bg-secondary';
                                if($p['nama_status'] == 'Belum Ditindaklanjuti') $badgeClass = 'bg-danger';
                                elseif($p['nama_status'] == 'Sedang Diproses') $badgeClass = 'bg-warning text-dark';
                                elseif($p['nama_status'] == 'Selesai') $badgeClass = 'bg-success';
                                elseif($p['nama_status'] == 'Ditutup') $badgeClass = 'bg-black text-secondary';
                            ?>
                            <span class="badge <?= $badgeClass ?>"><?= esc($p['nama_status']) ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <button type="button" class="btn btn-action text-primary btn-update" 
                                    data-id="<?= $p['id'] ?>"
                                    data-status="<?= $p['status_id'] ?>"
                                    data-catatan="<?= esc($p['catatan']) ?>"
                                    data-deskripsi="<?= esc($p['deskripsi']) ?>"
                                    data-bs-toggle="modal" data-bs-target="#updateModal" title="Update Status">
                                    <i class="bi bi-pencil-square"></i>
                                </button>
                                <a href="<?= base_url('petugas/pengaduan/delete/'.$p['id']) ?>" class="btn btn-action text-danger btn-delete" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tindak Lanjut Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('petugas/pengaduan/update_status') ?>" method="post" class="form-confirm">
                <div class="modal-body">
                    <input type="hidden" name="id" id="modal_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Masalah Dilaporkan:</label>
                        <p id="modal_deskripsi" class="p-2 border rounded small mb-0" style="background: rgba(255,255,255,0.03);"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Ubah Status</label>
                        <select name="status_id" id="modal_status" class="form-select">
                            <option value="1">Belum Ditindaklanjuti</option>
                            <option value="2">Sedang Diproses</option>
                            <option value="3">Selesai</option>
                            <option value="4">Ditutup</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan / Tindak Lanjut</label>
                        <textarea name="catatan" id="modal_catatan" class="form-control" rows="3" placeholder="Contoh: Sedang dalam perbaikan oleh teknisi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })

        // Handles
        const updateButtons = document.querySelectorAll('.btn-update');
        const modalId = document.getElementById('modal_id');
        const modalStatus = document.getElementById('modal_status');
        const modalCatatan = document.getElementById('modal_catatan');
        const modalDeskripsi = document.getElementById('modal_deskripsi');

        updateButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                modalId.value = this.getAttribute('data-id');
                modalStatus.value = this.getAttribute('data-status');
                modalCatatan.value = this.getAttribute('data-catatan');
                modalDeskripsi.textContent = this.getAttribute('data-deskripsi');
            });
        });
    });
</script>

<?= $this->endSection() ?>
