<?= $this->extend('petugas/layout') ?>
<?= $this->section('page_title'); ?>Inventaris Sarpras<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Daftar Barang</h1>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="" method="get" class="d-flex">
            <input type="text" name="q" class="form-control me-2" placeholder="Cari nama barang..." value="<?= esc($q ?? '') ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q): ?>
                <a href="<?= base_url('petugas/sarpras') ?>" class="btn btn-outline-secondary ms-2">Reset</a>
            <?php endif; ?>
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
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th>Total Unit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($items as $i => $item): ?>
                    <tr>
                        <td><?= $i+1 ?></td>
                        <td><?= esc($item['nama']) ?></td>
                        <td><?= esc($item['nama_kategori']) ?></td>
                        <td>
                            <span class="badge bg-success"><?= esc($item['tersedia']) ?> Tersedia</span>
                            <span class="badge bg-secondary"><?= esc($item['total_unit']) ?> Total</span>
                        </td>
                        <td>
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="<?= base_url('petugas/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="btn btn-action text-info" title="Lihat Unit">
                                    <i class="bi bi-list-ul"></i> Lihat Unit
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
