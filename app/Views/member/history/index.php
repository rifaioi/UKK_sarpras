<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Riwayat Peminjaman<?= $this->endSection(); ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-clock-history text-primary"></i> Riwayat Saya</h2>
        <p class="text-muted">Pantau status peminjaman dan pengaduan Anda.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <ul class="nav nav-tabs card-header-tabs" id="historyTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="loans-tab" data-bs-toggle="tab" data-bs-target="#loans" type="button" role="tab">
                    <i class="bi bi-arrow-left-right me-2"></i> Peminjaman
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="complaints-tab" data-bs-toggle="tab" data-bs-target="#complaints" type="button" role="tab">
                    <i class="bi bi-exclamation-triangle me-2"></i> Pengaduan
                </button>
            </li>
        </ul>
    </div>
    <div class="card-body">
        <div class="tab-content" id="historyTabsContent">
            
            <!-- Loans Tab -->
            <div class="tab-pane fade show active" id="loans" role="tabpanel">
                <div class="table-responsive">
                    <table class="table table-striped table-sm text-white">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Barang</th>
                                <th>Jumlah</th>
                                <th>Jadwal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($my_borrowings as $i => $b): ?>
                            <tr>
                                <td><?= $i+1 ?></td>
                                <td><?= date('d/m/Y', strtotime($b['tgl_pinjam'])) ?></td>
                                <td><?= esc($b['nama_barang']) ?></td>
                                <td><?= $b['jumlah'] ?> Unit</td>
                                <td>
                                    Pinjam: <?= date('d/m/y', strtotime($b['tgl_pinjam'])) ?><br>
                                    Kembali: <?= date('d/m/y', strtotime($b['tgl_kembali_rencana'])) ?>
                                </td>
                                <td>
                                    <?php 
                                        $badgeClass = 'bg-secondary';
                                        if($b['nama_status'] == 'Menunggu Persetujuan') $badgeClass = 'bg-warning text-dark';
                                        elseif($b['nama_status'] == 'Disetujui') $badgeClass = 'bg-primary';
                                        elseif($b['nama_status'] == 'Ditolak') $badgeClass = 'bg-danger';
                                        elseif($b['nama_status'] == 'Dikembalikan') $badgeClass = 'bg-success';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= $b['nama_status'] ?></span>
                                </td>
                                <td>
                                    <?php if($b['nama_status'] == 'Menunggu Persetujuan'): ?>
                                        <a href="<?= base_url('member/borrow/cancel/'.$b['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Batalkan pengajuan?')">Batal</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Complaints Tab -->
            <div class="tab-pane fade" id="complaints" role="tabpanel">
                <div class="list-group list-group-flush">
                    <?php if(empty($my_complaints)): ?>
                        <div class="text-center text-muted py-3">Belum ada riwayat pengaduan.</div>
                    <?php else: ?>
                        <?php foreach($my_complaints as $c): ?>
                        <div class="list-group-item">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1"><?= esc($c['judul']) ?></h5>
                                <small class="text-muted"><?= date('d/m/Y H:i', strtotime($c['created_at'])) ?></small>
                            </div>
                            <p class="mb-1"><?= esc($c['deskripsi']) ?></p>
                            <div class="mt-2">
                                <?php 
                                    $badgeClass = 'bg-secondary';
                                    if($c['nama_status'] == 'Belum Ditindaklanjuti') $badgeClass = 'bg-danger';
                                    elseif($c['nama_status'] == 'Sedang Diproses') $badgeClass = 'bg-warning text-dark';
                                    elseif($c['nama_status'] == 'Selesai') $badgeClass = 'bg-success';
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= $c['nama_status'] ?></span>
                                <small class="ms-2 text-muted"><i class="bi bi-geo-alt"></i> <?= esc($c['lokasi']) ?></small>
                                
                                <?php if($c['catatan']): ?>
                                    <div class="alert alert-info py-1 px-2 mt-2 mb-0 d-inline-block">
                                        <small><i class="bi bi-info-circle-fill"></i> <strong>Admin:</strong> <?= esc($c['catatan']) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
