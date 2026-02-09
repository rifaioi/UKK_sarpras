<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
Kelola Checklist - <?= esc($category['nama']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-3">
    <div class="col-md-8">
        <h2><a href="<?= base_url('admin/inspection-templates') ?>" class="text-decoration-none text-primary"><i class="bi bi-arrow-left me-2"></i></a> Kelola Checklist: <?= esc($category['nama']) ?></h2>
    </div>
</div>

<div class="row">
    <!-- Form Tambah -->
    <div class="col-md-4 mb-3">
        <div class="card h-100">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Tambah Item Checklist</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/inspection-templates/store') ?>" method="post">
                    <?= csrf_field() ?>
                    <input type="hidden" name="kategori_id" value="<?= $category['id'] ?>">
                    
                    <div class="mb-3">
                        <label for="nama_item" class="form-label">Nama Item / Bagian yang Dicek</label>
                        <input type="text" class="form-control" id="nama_item" name="nama_item" placeholder="Contoh: Layar, Keyboard, Kabel Power" required>
                    </div>
                    
                    <button type="submit" class="btn btn-tambah w-100">
                        <i class="bi bi-plus-circle me-1"></i> Tambah Item
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- List Item -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Daftar Item Checklist</h5>
            </div>
            <div class="card-body">
                <?php if (empty($items)) : ?>
                    <div class="alert alert-info">Belum ada item checklist untuk kategori ini.</div>
                <?php else : ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent border-secondary border-opacity-25">
                                <span class="text-white"><?= esc($item['nama_item']) ?></span>
                                <a href="<?= base_url('admin/inspection-templates/delete/' . $item['id']) ?>" class="btn btn-action text-danger" onclick="return confirm('Hapus item ini?')" title="Delete">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
