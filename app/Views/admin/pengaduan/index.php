<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Daftar Pengaduan<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Manajemen Pengaduan</h1>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
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
                        <a href="<?= base_url('uploads/pengaduan/' . $p['bukti_foto']) ?>" target="_blank" class="btn btn-sm btn-info">Lihat</a>
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
                    <?php if($p['catatan']): ?>
                        <div class="mt-1 small text-info" style="font-size: 0.75rem;">
                            <i class="bi bi-chat-dots me-1"></i><?= esc($p['catatan']) ?>
                        </div>
                    <?php endif; ?>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-primary btn-update" 
                        data-id="<?= $p['id'] ?>"
                        data-status="<?= $p['status_id'] ?>"
                        data-catatan="<?= esc($p['catatan']) ?>"
                        data-deskripsi="<?= esc($p['deskripsi']) ?>"
                        data-bs-toggle="modal" data-bs-target="#updateModal">
                        Update
                    </button>
                    <a href="<?= base_url('admin/pengaduan/delete/'.$p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Update Modal -->
<div class="modal fade" id="updateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Admin: Update Pengaduan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/pengaduan/update_status') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="id" id="modal_id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Detail Pengaduan:</label>
                        <p id="modal_deskripsi" class="p-2 bg-light border rounded small mb-0"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status_id" id="modal_status" class="form-select">
                            <option value="1">Belum Ditindaklanjuti</option>
                            <option value="2">Sedang Diproses</option>
                            <option value="3">Selesai</option>
                            <option value="4">Ditutup</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Catatan Admin / Tindak Lanjut</label>
                        <textarea name="catatan" id="modal_catatan" class="form-control" rows="3" placeholder="Contoh: Menunggu keputusan manajemen..."></textarea>
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
