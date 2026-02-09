<?= $this->extend('admin/layout') ?>

<?= $this->section('page_title') ?>
Recycle Bin Checklist - <?= esc($category['nama']) ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row mb-3">
    <div class="col-md-8">
        <h2><a href="<?= base_url('admin/inspection-templates/manage/' . $category['id']) ?>" class="text-decoration-none text-primary"><i class="bi bi-arrow-left me-2"></i></a> Recycle Bin Checklist: <?= esc($category['nama']) ?></h2>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header pb-0">
                <h5 class="card-title mb-0">Item Checklist Terhapus</h5>
            </div>
            <div class="card-body">
                <?php if (empty($items)) : ?>
                    <div class="alert alert-info border-0 shadow-none" style="background: rgba(255,255,255,0.03);">Tidak ada item checklist terhapus untuk kategori ini.</div>
                <?php else : ?>
                    <ul class="list-group list-group-flush">
                        <?php foreach ($items as $item) : ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent border-secondary border-opacity-25 text-decoration-line-through text-muted">
                                <span><?= esc($item['nama_item']) ?></span>
                                <a href="<?= base_url('admin/inspection-templates/restore/' . $item['id']) ?>" class="btn btn-action text-success btn-confirm" title="Restore">
                                    <i class="bi bi-arrow-counterclockwise"></i> Restore
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
