<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Riwayat Peminjaman<?= $this->endSection(); ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col-12">
        <h2><i class="bi bi-clock-history text-primary"></i> Riwayat Saya</h2>
        <p class="text-muted">Pantau status peminjaman dan pengaduan Anda.</p>
    </div>
</div>

<div class="card border-0">
    <div class="card-header border-bottom-0 pt-4 pb-0">
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
                                <th>Kode Pinjam</th>
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
                                <td><code class="text-primary"><?= esc($b['kode_peminjaman'] ?? '-') ?></code></td>
                                <td><?= date('d/m/Y', strtotime($b['tgl_pinjam'])) ?></td>
                                <td><?= esc($b['nama_barang']) ?></td>
                                <td><?= $b['jumlah'] ?> Unit</td>
                                <td>
                                    Pinjam: <?= date('d/m/Y', strtotime($b['tgl_pinjam'])) ?><br>
                                    Kembali: <?= date('d/m/Y', strtotime($b['tgl_kembali_rencana'])) ?>
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
                                    <?php if($b['nama_status'] == 'Ditolak' && !empty($b['rejection_reason'])): ?>
                                        <div class="mt-1 small text-danger fw-bold">
                                            Alasan: <span class="text-white-50 fw-normal"><?= esc($b['rejection_reason']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($b['nama_status'] == 'Menunggu Persetujuan'): ?>
                                        <a href="<?= base_url('member/borrow/cancel/'.$b['id']) ?>" class="btn btn-action text-danger" onclick="return confirm('Batalkan pengajuan?')" title="Cancel">
                                            <i class="bi bi-x-circle me-1"></i> Batal
                                        </a>
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
                <div class="table-responsive">
                    <table class="table table-striped table-sm text-white">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Judul Laporan</th>
                                <th>Lokasi</th>
                                <th>Status</th>
                                <th>Catatan Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(empty($my_complaints)): ?>
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-3">Belum ada riwayat pengaduan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($my_complaints as $i => $c): ?>
                                <tr>
                                    <td><?= $i+1 ?></td>
                                    <td><?= date('d/m/Y', strtotime($c['created_at'])) ?></td>
                                    <td>
                                        <div class="fw-bold"><?= esc($c['judul']) ?></div>
                                        <small class="text-white-50"><?= esc($c['deskripsi']) ?></small>
                                    </td>
                                    <td><?= esc($c['lokasi']) ?></td>
                                    <td>
                                        <?php 
                                            $badgeClass = 'bg-secondary';
                                            if($c['nama_status'] == 'Belum Ditindaklanjuti') $badgeClass = 'bg-danger';
                                            elseif($c['nama_status'] == 'Sedang Diproses') $badgeClass = 'bg-warning text-dark';
                                            elseif($c['nama_status'] == 'Selesai') $badgeClass = 'bg-success';
                                        ?>
                                        <span class="badge <?= $badgeClass ?>"><?= $c['nama_status'] ?></span>
                                    </td>
                                    <td>
                                        <?php if($c['catatan']): ?>
                                            <small class="text-info"><i class="bi bi-info-circle"></i> <?= esc($c['catatan']) ?></small>
                                        <?php else: ?>
                                            <small class="text-muted">-</small>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>
