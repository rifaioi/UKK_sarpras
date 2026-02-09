<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
Checklist Pemeriksaan
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-3">
    <div class="col-md-6">
        <h2>Kriteria Checklist per Kategori</h2>
        <p class="text-muted">Kelola poin-poin checklist inspeksi untuk setiap kategori alat.</p>
    </div>
</div>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Item Checklist</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($categories)) : ?>
                        <tr>
                            <td colspan="4" class="text-center">Belum ada kategori.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($categories as $index => $cat) : 
                            // Hitung jumlah item checklist (opsional, bisa dioptimasi join)
                            $db = \Config\Database::connect();
                            $count = $db->table('inspection_template_items')
                                        ->where('kategori_id', $cat['id'])
                                        ->where('is_deleted', 0)
                                        ->countAllResults();
                        ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= esc($cat['nama']) ?></td>
                                <td>
                                    <span class="badge bg-info text-dark"><?= $count ?> Items</span>
                                </td>
                                <td>
                                    <a href="<?= base_url('admin/inspection-templates/manage/' . $cat['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="bi bi-list-check"></i> Kelola Checklist
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
