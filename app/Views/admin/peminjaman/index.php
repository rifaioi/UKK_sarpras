<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Data Peminjaman<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Admin: Approval Peminjaman</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/peminjaman/archived') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-trash"></i> Recycle Bin
        </a>
    </div>
</div><div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?= base_url('admin/peminjaman') ?>" method="get" class="row g-3 align-items-end">
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
                    <option value="1" <?= $filter_status == '1' ? 'selected' : '' ?>>Menunggu Approval</option>
                    <option value="2" <?= $filter_status == '2' ? 'selected' : '' ?>>Disetujui (Aktif)</option>
                    <option value="3" <?= $filter_status == '3' ? 'selected' : '' ?>>Ditolak</option>
                    <option value="4" <?= $filter_status == '4' ? 'selected' : '' ?>>Dikembalikan</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-sm btn-primary w-100">
                    <i class="bi bi-filter"></i> Filter
                </button>
            </div>
            <div class="col-12 mt-2">
                <div class="collapse" id="extraFilters">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small text-white-50">Cari (Kode/Nama)</label>
                            <input type="text" name="q" class="form-control form-control-sm bg-dark text-white border-secondary" value="<?= esc($filter_q ?? '') ?>" placeholder="Search...">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-white-50">Dari Tanggal</label>
                            <input type="date" name="date_from" class="form-control form-control-sm bg-dark text-white border-secondary" value="<?= esc($filter_date_from ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-white-50">Sampai Tanggal</label>
                            <input type="date" name="date_to" class="form-control form-control-sm bg-dark text-white border-secondary" value="<?= esc($filter_date_to ?? '') ?>">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-link btn-sm text-white-50 p-0 text-decoration-none" data-bs-toggle="collapse" data-bs-target="#extraFilters">
                    <i class="bi bi-chevron-down"></i> Filter Tambahan (Pencarian/Tanggal)
                </button>
            </div>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pinjam</th>
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
                        <td><code class="text-primary"><?= esc($p['kode_peminjaman'] ?? '-') ?></code></td>
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
                            <div class="d-flex gap-1 justify-content-center">
                                <?php if($p['nama_status'] == 'Menunggu Persetujuan'): ?>
                                    <a href="<?= base_url('admin/peminjaman/approve/'.$p['id']) ?>" class="btn btn-action text-success btn-confirm" title="Approve">
                                        <i class="bi bi-check-lg"></i>
                                    </a>
                                    <button type="button" class="btn btn-action text-danger" title="Reject" data-bs-toggle="modal" data-bs-target="#rejectModal<?= $p['id'] ?>">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                <?php endif; ?>
                                <?php if($p['nama_status'] == 'Disetujui'): ?>
                                    <a href="<?= base_url('admin/peminjaman/print/'.$p['id']) ?>" class="btn btn-action text-info" title="Cetak" target="_blank">
                                        <i class="bi bi-printer"></i>
                                    </a>
                                <?php endif; ?>

                                <a href="<?= base_url('admin/peminjaman/delete/'.$p['id']) ?>" class="btn btn-action text-muted btn-delete" title="Hapus">
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

<!-- Rejection Modals -->
<?php foreach($peminjaman as $p): ?>
<div class="modal fade" id="rejectModal<?= $p['id'] ?>" tabindex="-1" aria-labelledby="rejectModalLabel<?= $p['id'] ?>" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="<?= base_url('admin/peminjaman/reject/'.$p['id']) ?>" method="post" class="form-confirm">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel<?= $p['id'] ?>">Tolak Peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menolak peminjaman ini?</p>
                    <p><strong>Peminjam:</strong> <?= esc($p['nama_lengkap']) ?></p>
                    <p><strong>Barang:</strong> <?= esc($p['nama_barang']) ?> (<?= $p['jumlah'] ?>)</p>
                    
                    <div class="mb-3">
                        <label for="rejection_reason<?= $p['id'] ?>" class="form-label">Alasan Penolakan (Opsional)</label>
                        <textarea class="form-control" id="rejection_reason<?= $p['id'] ?>" name="rejection_reason" rows="3" placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?= $this->endSection() ?>
