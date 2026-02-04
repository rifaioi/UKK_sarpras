<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Persetujuan Peminjaman<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Peminjaman</h1>
</div><div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?= base_url('petugas/peminjaman') ?>" method="get" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small text-white-50">Filter Peminjam</label>
                <select name="user_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">Semua Peminjam</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $filter_user == $u['id'] ? 'selected' : '' ?>><?= esc($u['nama_lengkap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-white-50">Filter Barang (Alat)</label>
                <select name="sarpras_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">Semua Barang</option>
                    <?php foreach($sarpras_list as $s): ?>
                        <option value="<?= $s['id'] ?>" <?= $filter_sarpras == $s['id'] ? 'selected' : '' ?>><?= esc($s['nama']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label small text-white-50">Status</label>
                <select name="status_id" class="form-select form-select-sm bg-dark text-white border-secondary">
                    <option value="">Semua Status</option>
                    <option value="1" <?= $filter_status == '1' ? 'selected' : '' ?>>Menunggu Persetujuan</option>
                    <option value="2" <?= $filter_status == '2' ? 'selected' : '' ?>>Disetujui/Dipinjam (Aktif)</option>
                    <option value="3" <?= $filter_status == '3' ? 'selected' : '' ?>>Ditolak</option>
                    <option value="4" <?= $filter_status == '4' ? 'selected' : '' ?>>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>
<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Jumlah</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($peminjaman as $i => $p): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($p['nama_lengkap']) ?></td>
                <td><?= esc($p['nama_barang']) ?></td>
                <td><?= esc($p['jumlah']) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_pinjam'])) ?></td>
                <td><?= date('d/m/Y', strtotime($p['tgl_kembali_rencana'])) ?></td>
                <td>
                    <?php 
                        $badgeClass = 'bg-secondary';
                        if($p['nama_status'] == 'Menunggu Persetujuan') $badgeClass = 'bg-warning text-dark';
                        elseif($p['nama_status'] == 'Disetujui') $badgeClass = 'bg-primary';
                        elseif($p['nama_status'] == 'Ditolak') $badgeClass = 'bg-danger';
                        elseif($p['nama_status'] == 'Dikembalikan') $badgeClass = 'bg-success';
                    ?>
                    <span class="badge <?= $badgeClass ?>"><?= esc($p['nama_status']) ?></span>
                </td>
                <td>
                    <?php if($p['nama_status'] == 'Menunggu Persetujuan'): ?>
                        <a href="<?= base_url('petugas/peminjaman/approve/'.$p['id']) ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui peminjaman?')">Approve</a>
                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal" data-id="<?= $p['id'] ?>">
                            Reject
                        </button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="" method="post" id="rejectForm">
            <div class="modal-content bg-dark text-white border-secondary">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title" id="rejectModalLabel">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label text-white-50">Alasan Penolakan (Opsional)</label>
                        <textarea class="form-control bg-dark text-white border-secondary" name="rejection_reason" id="rejection_reason" rows="3" placeholder="Contoh: Stok sedang habis dipesan orang lain, atau barang sedang dalam pemeliharaan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const form = document.getElementById('rejectForm');
            form.action = "<?= base_url('petugas/peminjaman/reject') ?>/" + id;
        });
    }
</script>
<?= $this->endSection() ?>
