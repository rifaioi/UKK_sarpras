<?= $this->extend('petugas/layout') ?>
<?= $this->Section('page_title'); ?>Persetujuan Peminjaman<?= $this->endSection(); ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Peminjaman</h1>
</div>

<?php if(session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
<?php endif; ?>
<?php if(session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
<?php endif; ?>

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
                        <a href="<?= base_url('petugas/peminjaman/reject/'.$p['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak peminjaman?')">Reject</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
