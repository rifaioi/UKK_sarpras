<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>Detail Unit: <?= esc($item['kode']) ?><?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('admin/sarpras') ?>" class="text-decoration-none">Inventaris</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('admin/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="text-decoration-none"><?= esc($item['nama']) ?></a></li>
            <li class="breadcrumb-item active text-white-50" aria-current="page"><?= esc($item['kode']) ?></li>
        </ol>
    </nav>
    <h1 class="h2">Detail Unit</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Unit
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4 text-white">
                <div class="text-center mb-4 border-bottom border-secondary pb-3">
                    <h3 class="mb-0 text-white"><?= esc($item['nama']) ?></h3>
                    <code class="fs-5 text-primary"><?= esc($item['kode']) ?></code>
                </div>

                <table class="table table-borderless mt-4 text-white">
                    <tr>
                        <th width="40%">Kategori</th>
                        <td>: <?= esc($item['nama_kategori']) ?></td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>: <?= esc($item['nama_lokasi']) ?></td>
                    </tr>
                    <tr>
                        <th>Kondisi</th>
                        <td>: 
                            <?php
                                $badge = $item['kondisi_id'] == 1 ? 'bg-success' : ($item['kondisi_id'] == 2 ? 'bg-warning text-dark' : 'bg-danger');
                            ?>
                            <span class="badge <?= $badge ?>"><?= esc($item['nama_kondisi']) ?></span>
                        </td>
                    </tr>
                    <tr>
                        <th>Status Unit</th>
                        <td>: 
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
                    </tr>
                    <tr>
                        <th>Terdaftar pada</th>
                        <td>: <?= date('d M Y, H:i', strtotime($item['created_at'])) ?></td>
                    </tr>
                </table>

                <div class="d-grid gap-2 mt-4">
                    <a href="<?= base_url('admin/sarpras/edit/'.$item['id']) ?>" class="btn btn-warning rounded-pill">
                        <i class="bi bi-pencil me-2"></i> Edit Data Unit
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card border-0 shadow-sm bg-dark">
            <div class="card-header bg-transparent border-0 pt-4 px-4">
                <h5 class="card-title text-white">Riwayat / Aktivitas Unit</h5>
            </div>
            <div class="card-body p-4 text-white">
                <div class="alert alert-info border-0 bg-info bg-opacity-10 text-info">
                    <i class="bi bi-info-circle me-2"></i> Setiap unit barang memiliki identitas unik untuk keperluan pelacakan peminjaman dan pemeliharaan secara spesifik.
                </div>
                
                <div class="timeline mt-4">
                    <div class="d-flex mb-3">
                        <div class="me-3">
                            <i class="bi bi-plus-circle-fill text-success fs-4"></i>
                        </div>
                        <div class="border-start border-secondary ps-3 pb-3">
                            <small class="text-white-50 d-block mb-1"><?= date('d M Y, H:i', strtotime($item['created_at'])) ?></small>
                            <h6 class="mb-1 text-white">Barang Terdaftar</h6>
                            <p class="mb-0 text-white-50 small">Unit baru ditambahkan ke sistem di lokasi <strong><?= esc($item['nama_lokasi']) ?></strong> dengan kondisi <strong><?= esc($item['nama_kondisi']) ?></strong>.</p>
                        </div>
                    </div>
                    
                    <div class="d-flex">
                        <div class="me-3">
                            <i class="bi bi-info-circle-fill text-info fs-4"></i>
                        </div>
                        <div class="ps-3">
                            <h6 class="mb-1 text-white">Status Saat Ini</h6>
                            <p class="mb-0 text-white-50 small">Unit saat ini berstatus <strong><?= strtoupper($item['status']) ?></strong>.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
