<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title'); ?>Inventaris Sarpras<?= $this->endSection(); ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Inventaris Sarpras</h1>
    <div class="btn-toolbar mb-2 mb-md-0 gap-2">
        <a href="<?= base_url('admin/sarpras/trash') ?>" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-trash"></i> Recycle Bin
        </a>
        <a href="<?= base_url('admin/sarpras/create') ?>" class="btn btn-tambah btn-sm-tambah">
            <i class="bi bi-plus-lg me-1"></i> Tambah Barang
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6">
        <form action="" method="get" class="d-flex">
            <input type="text" name="q" class="form-control me-2" placeholder="Cari nama barang..." value="<?= esc($q ?? '') ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
            <?php if ($q): ?>
                <a href="<?= base_url('admin/sarpras') ?>" class="btn btn-outline-secondary ms-2">Reset</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
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
            <?php foreach ($items as $i => $item): ?>
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
                        <a href="<?= base_url('admin/sarpras/units?nama='.urlencode($item['nama']).'&kategori_id='.$item['kategori_id']) ?>" class="btn btn-action btn-sm btn-info text-white" title="Lihat Unit">
                            <i class="bi bi-list-ul"></i> Lihat Unit
                        </a>
                        <button type="button" class="btn btn-action btn-sm btn-warning btn-edit-group" 
                                data-nama="<?= esc($item['nama']) ?>" 
                                data-kategori="<?= $item['kategori_id'] ?>" 
                                title="Edit Nama Grup">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <a href="<?= base_url('admin/sarpras/delete_group/'.$item['kategori_id'].'?nama='.urlencode($item['nama'])) ?>" class="btn btn-action btn-sm text-danger btn-delete" title="Hapus Grup">
                            <i class="bi bi-trash"></i>
                        </a>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit Nama Grup -->
<div class="modal fade" id="editGroupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content bg-dark text-white border-secondary">
            <div class="modal-header border-secondary">
                <h5 class="modal-title">Ganti Nama Barang</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="<?= base_url('admin/sarpras/rename_group') ?>" method="post">
                <div class="modal-body">
                    <input type="hidden" name="old_name" id="old_name">
                    <input type="hidden" name="kategori_id" id="kategori_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Barang (Lama)</label>
                        <input type="text" class="form-control bg-secondary border-0 text-white" id="display_old_name" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Nama Barang Baru</label>
                        <input type="text" name="new_name" id="new_name" class="form-control" required placeholder="Masukkan nama baru...">
                        <div class="form-text text-white-50">Menamai ulang grup akan mengubah nama pada SEMUA unit barang ini.</div>
                    </div>
                </div>
                <div class="modal-footer border-secondary">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editGroupModal = new bootstrap.Modal(document.getElementById('editGroupModal'));
    
    document.querySelectorAll('.btn-edit-group').forEach(btn => {
        btn.addEventListener('click', function() {
            const nama = this.getAttribute('data-nama');
            const kategori = this.getAttribute('data-kategori');
            
            document.getElementById('old_name').value = nama;
            document.getElementById('display_old_name').value = nama;
            document.getElementById('new_name').value = nama;
            document.getElementById('kategori_id').value = kategori;
            
            editGroupModal.show();
        });
    });
});
</script>

<?= $this->endSection() ?>
