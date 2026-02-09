<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title') ?>Priority Maintenance Dashboard<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Priority Maintenance Dashboard</h1>
</div>

<div class="alert alert-info border-0 shadow-sm">
    <div class="d-flex align-items-center">
        <i class="bi bi-lightbulb-fill fs-4 text-warning me-3"></i>
        <div>
            <h6 class="mb-1 fw-bold">Bagaimana urutan ini ditentukan?</h6>
            <p class="mb-0 small">Sistem menghitung <strong>Skor Prioritas</strong> dengan menggabungkan <strong>seberapa sering alat rusak</strong> dan <strong>sudah berapa lama sejak terakhir servis</strong>. Semakin tinggi skornya, semakin mendesak alat tersebut untuk diperiksa.</p>
        </div>
    </div>
</div>

<?php if(empty($priorities)): ?>
    <div class="alert alert-success">
        <i class="bi bi-check-circle"></i> Tidak ada barang yang memerlukan prioritas maintenance saat ini.
    </div>
<?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-sm text-white">
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Nama Barang</th>
                    <th>Kode</th>
                    <th>Sering Dipinjam</th>
                    <th>Total Kerusakan</th>
                    <th>Terakhir Servis</th>
                    <th>Skor</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($priorities as $i => $p): ?>
                <tr>
                    <td>
                        <?php if($i < 3): ?>
                            <span class="badge bg-danger">URGENT</span>
                        <?php elseif($i < 7): ?>
                            <span class="badge bg-warning text-dark">HIGH</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">MEDIUM</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?= esc($p['nama_barang']) ?></strong></td>
                    <td><code><?= esc($p['kode']) ?></code></td>
                    <td><?= $p['breakdown_count'] ?>x</td>
                    <td>
                        <span class="badge bg-danger"><?= $p['damage_count'] ?>x rusak</span>
                    </td>
                    <td>
                        <?= $p['last_maintenance_date'] ?>
                        <?php if($p['last_maintenance_date'] != 'Belum pernah'): ?>
                            <br><small class="text-muted">(<?= $p['days_since_maintenance'] ?> hari lalu)</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong class="text-warning"><?= $p['priority_score'] ?></strong>
                    </td>
                    <td>
                        <div class="dropdown">
                            <button class="btn btn-sm btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Pilih Tindakan
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark shadow">
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('admin/maintenance/records/create/'.$p['sarpras_id']) ?>">
                                        <i class="bi bi-wrench me-2 text-primary"></i> Servis Sekarang
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('admin/maintenance/records/history/'.$p['sarpras_id']) ?>">
                                        <i class="bi bi-clock-history me-2 text-info"></i> Lihat Riwayat
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= base_url('admin/maintenance/records/timeline/'.$p['sarpras_id']) ?>">
                                        <i class="bi bi-diagram-3 me-2 text-success"></i> Lihat Timeline
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="<?= base_url('admin/sarpras/delete/'.$p['sarpras_id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus barang ini secara permanen?')">
                                        <?= csrf_field() ?>
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-trash me-2"></i> Hapus Barang
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
