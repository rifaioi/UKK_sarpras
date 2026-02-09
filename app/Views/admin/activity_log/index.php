<?= $this->extend('admin/layout') ?>
<?= $this->Section('page_title'); ?>Log Aktivitas User<?= $this->endSection(); ?>
<?= $this->section('content') ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Log Aktivitas User</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <a href="<?= base_url('admin/log/export') ?>" class="btn btn-action text-success">
            <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export CSV
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form action="<?= base_url('admin/activity-log') ?>" method="get" class="row g-3">
            <div class="col-md-4">
                <label class="form-label small text-white-50">Filter User</label>
                <select name="user_id" class="form-select form-select-sm">
                    <option value="">Semua User</option>
                    <?php foreach($users as $u): ?>
                        <option value="<?= $u['id'] ?>" <?= $filter_user == $u['id'] ? 'selected' : '' ?>><?= esc($u['nama_lengkap']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label small text-white-50">Aksi</label>
                <input type="text" name="action" class="form-control form-control-sm" value="<?= esc($filter_action ?? '') ?>" placeholder="Search action...">
            </div>
            <div class="col-md-4 d-flex align-items-end">
                <button type="submit" class="btn btn-tambah btn-sm-tambah w-100">
                    <i class="bi bi-filter me-1"></i> Filter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-sm text-white">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Role</th>
                <th>Aksi</th>
                <th>Deskripsi</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logs as $i => $log): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= esc($log['nama_lengkap'] ?: 'System') ?></td>
                <td><span class="badge bg-secondary"><?= esc($log['nama_role']) ?></span></td>
                <td><span class="badge bg-primary"><?= esc($log['aksi']) ?></span></td>
                <td><?= esc($log['deskripsi']) ?></td>
                <td><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
