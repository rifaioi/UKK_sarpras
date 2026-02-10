<?= $this->extend('member/layout') ?>
<?= $this->Section('page_title'); ?>Lapor Kerusakan<?= $this->endSection(); ?>

<?= $this->section('content') ?>



<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">Buat Pengaduan</div>
            <div class="card-body">
                <form action="<?= base_url('member/pengaduan/store') ?>" method="post" enctype="multipart/form-data" class="form-confirm">
                    <div class="mb-3">
                        <label class="form-label">Judul Laporan</label>
                        <input type="text" class="form-control" name="judul" required placeholder="Contoh: AC Bocor">
                    </div>
                    
                    <div class="mb-3">
                         <label class="form-label">Jenis Sarpras (Opsional)</label>
                         <select class="form-select" name="sarpras_id">
                            <option value="">-- Pilih Barang --</option>
                            <?php foreach($items as $item): ?>
                                <option value="<?= $item['id'] ?>"><?= esc($item['nama']) ?> (<?= esc($item['kode']) ?>)</option>
                            <?php endforeach; ?>
                         </select>
                    </div>
                    
                    <div class="mb-3">
                         <label class="form-label">Lokasi</label>
                         <select class="form-select" name="lokasi" required>
                            <option value="">-- Pilih Lokasi --</option>
                            <?php foreach($locations as $loc): ?>
                                <option value="<?= esc($loc['nama_lokasi']) ?>"><?= esc($loc['nama_lokasi']) ?></option>
                            <?php endforeach; ?>
                         </select>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Foto (Opsional)</label>
                        <input type="file" class="form-control" name="bukti_foto" accept="image/*">
                    </div>

                    <div class="d-flex gap-2 mt-4">
                        <button type="submit" class="btn btn-primary px-4 flex-grow-1">
                            <i class="bi bi-send me-2"></i>Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>
