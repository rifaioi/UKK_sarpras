<?= $this->extend('admin/layout') ?>
<?= $this->section('page_title') ?>Jadwal Maintenance<?= $this->endSection() ?>
<?= $this->section('content') ?>

<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Jadwal Maintenance</h1>
    <a href="<?= base_url('admin/maintenance/schedules/create') ?>" class="btn btn-sm btn-primary">
        <i class="bi bi-plus-lg"></i> Tambah Jadwal
    </a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>Kategori</th>
                <th>Nama Jadwal</th>
                <th>Interval</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if(empty($schedules)): ?>
            <tr>
                <td colspan="6" class="text-center">Belum ada jadwal maintenance</td>
            </tr>
            <?php else: ?>
                <?php foreach($schedules as $i => $schedule): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><?= esc($schedule['nama_kategori']) ?></td>
                    <td><strong><?= esc($schedule['schedule_name']) ?></strong></td>
                    <td><span class="badge bg-info"><?= $schedule['interval_months'] ?> bulan</span></td>
                    <td><?= esc(substr($schedule['description'] ?? '', 0, 60)) ?><?= strlen($schedule['description'] ?? '') > 60 ? '...' : '' ?></td>
                    <td>
                        <a href="<?= base_url('admin/maintenance/schedules/edit/'.$schedule['id']) ?>" class="btn btn-sm btn-warning">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?= base_url('admin/maintenance/schedules/delete/'.$schedule['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus jadwal ini?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
