<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Recycle Bin: Pengaduan<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Recycle Bin: Pengaduan</h1>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <a href="<?= base_url('admin/pengaduan') ?>" class="btn btn-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Kembali ke Data Aktif
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl</th>
                        <th>Pelapor</th>
                        <th>Judul & Lokasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($pengaduan)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-3 text-muted italic">Tidak ada data di tempat sampah.</td>
                        </tr>
                    <?php endif; ?>
                    <?php foreach($pengaduan as $i => $p): ?>
                    <tr class="text-decoration-line-through text-muted">
                        <td><?= $i+1 ?></td>
                        <td><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                        <td><?= esc($p['nama_lengkap']) ?></td>
                        <td>
                            <strong><?= esc($p['judul']) ?></strong><br>
                            <small>(<?= esc($p['lokasi']) ?>)</small>
                        </td>
                        <td>
                            <span class="badge bg-secondary"><?= esc($p['nama_status']) ?></span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="<?= base_url('admin/pengaduan/restore/'.$p['id']) ?>" class="btn btn-action text-success btn-confirm" title="Restore">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
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
<?= $this->endSection() ?>
