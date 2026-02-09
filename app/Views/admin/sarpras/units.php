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
        <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-sm btn-outline-secondary me-2">
            <i class="bi bi-arrow-left"></i> Kembali
        </a>
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
