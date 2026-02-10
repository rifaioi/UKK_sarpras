<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>Daftar Unit: <?= esc($nama_barang) ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/sarpras') ?>" class="text-decoration-none">Inventaris</a></li>
            <li class="breadcrumb-item active text-white-50" aria-current="page"><?= esc($nama_barang) ?></li>
        </ol>
    </nav>
    <h1 class="h2">Daftar Unit: <?= esc($nama_barang) ?></h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <button type="button" class="btn btn-sm btn-primary me-2" data-bs-toggle="collapse" data-bs-target="#collapseAddUnit">
            <i class="bi bi-plus-lg"></i> Tambah Unit
        </button>
        <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
    </div>
</div>

<!-- Form Tambah Unit (Collapse) -->
<div class="collapse mb-4" id="collapseAddUnit">
    <div class="card border-0 shadow-sm bg-dark bg-opacity-50">
        <div class="card-body p-4">
            <h5 class="text-white mb-3">Tambah Unit Baru untuk <?= esc($nama_barang) ?></h5>
            <form action="<?= base_url('admin/sarpras/store') ?>" method="post" class="form-confirm">
                <input type="hidden" name="nama" value="<?= esc($nama_barang) ?>">
                <input type="hidden" name="kategori_id" value="<?= esc($kategori_id) ?>">
                <input type="hidden" name="redirect_to" value="units"> <!-- Signal to redirect back to units list -->
                
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label class="form-label small text-white-50">Lokasi Penempatan</label>
                        <select class="form-select" name="location_id" required>
                            <option value="">-- Pilih Lokasi --</option>
                            <?php foreach($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['nama_lokasi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label small text-white-50">Kondisi Awal</label>
                        <select class="form-select" name="kondisi_id" required>
                            <?php foreach($conditions as $cond): ?>
                                <option value="<?= $cond['id'] ?>"><?= esc($cond['nama_kondisi']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label class="form-label small text-white-50">Jumlah Unit</label>
                        <input type="number" name="stok" class="form-control" value="1" min="1" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-save me-2"></i>Simpan Unit
                            </button>
                            <button type="button" class="btn btn-action px-4" data-bs-toggle="collapse" data-bs-target="#collapseAddUnit">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Inventaris</th>
                <th>Lokasi</th>
                <th>Kondisi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $i => $item): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><code><?= esc($item['kode']) ?></code></td>
                <td><?= esc($item['nama_lokasi']) ?></td>
                <td>
                    <?php
                        $badge = $item['kondisi_id'] == 1 ? 'bg-success' : ($item['kondisi_id'] == 2 ? 'bg-warning text-dark' : 'bg-danger');
                    ?>
                    <span class="badge <?= $badge ?>"><?= esc($item['nama_kondisi']) ?></span>
                </td>
                <td>
                    <?php
                        $statusBadge = 'bg-secondary';
                        $statusLabel = strtoupper($item['status']);
                        if ($item['status'] == 'tersedia') $statusBadge = 'bg-success';
                        elseif ($item['status'] == 'dipinjam') $statusBadge = 'bg-primary';
                        elseif ($item['status'] == 'rusak') $statusBadge = 'bg-warning text-dark';
                        elseif ($item['status'] == 'hilang') $statusBadge = 'bg-danger';
                    ?>
                    <span class="badge <?= $statusBadge ?>"><?= $statusLabel ?></span>
                </td>
                <td>
                    <div class="btn-group">
                        <a href="<?= base_url('admin/sarpras/show/'.$item['id']) ?>" class="btn btn-sm btn-info text-white" title="Detail"><i class="bi bi-eye"></i></a>
                        <a href="<?= base_url('admin/sarpras/edit/'.$item['id']) ?>" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                        <a href="<?= base_url('admin/sarpras/delete/'.$item['id']) ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus"><i class="bi bi-trash"></i></a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
