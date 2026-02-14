<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>
    Sedang Dalam Perbaikan
<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Sedang Dalam Perbaikan</h1>
</div>

    <?php if(empty($barang_rusak)): ?>
        <div class="alert alert-info border-0 bg-secondary bg-opacity-25 text-white">
            <i class="bi bi-info-circle me-2"></i> Tidak ada barang yang sedang dalam perbaikan.
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-sm text-white">
                <thead>
                    <tr>
                        <th>Kode Unit</th>
                        <th>Barang</th>
                        <th>Lokasi</th>
                        <th>Kondisi</th>
                        <th>Peminjam Terakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($barang_rusak as $item): ?>
                    <tr>
                        <td><code><?= esc($item['kode']) ?></code></td>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['nama_lokasi']) ?></td>
                        <td><span class="badge bg-danger"><?= esc($item['nama_kondisi']) ?></span></td>
                        <td>
                            <?php if($item['peminjam_terakhir']): ?>
                                <span class="text-white"><?= esc($item['peminjam_terakhir']) ?></span>
                                <div class="small opacity-75 text-info"><?= date('d/m/Y', strtotime($item['tgl_pengembalian'])) ?></div>
                            <?php else: ?>
                                <span class="text-info opacity-75 small italic">Update Manual</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="d-flex gap-1 flex-wrap">
                                <a href="<?= base_url('admin/maintenance/records/create/' . $item['id']) ?>" 
                                   class="btn btn-sm btn-info mb-1" title="Buat Jadwal Perbaikan">
                                    <i class="bi bi-calendar-plus me-1"></i> Perbaikan
                                </a>
                                <a href="<?= base_url('admin/pengembalian/restock/' . $item['id']) ?>" 
                                   class="btn btn-sm btn-success mb-1" title="Tandai Selesai Diperbaiki">
                                    <i class="bi bi-check-circle me-1"></i> Selesai
                                </a>
                                <a href="<?= base_url('admin/pengembalian/scrap/' . $item['id']) ?>" 
                                   class="btn btn-sm btn-danger mb-1" title="Tandai Tidak Bisa Diperbaiki">
                                    <i class="bi bi-trash me-1"></i> Scrap
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?= $this->endSection() ?>
