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
                <th style="width: 10%">Tanggal</th>
                <th style="width: 10%">User</th>
                <th style="width: 5%">Aksi</th>
                <th style="width: 10%">Modul</th>
                <th style="width: 15%">Aktivitas</th>
                <th style="width: 25%">Metadata</th>
                <th style="width: 5%">IP Address</th>
                <th style="width: 20%">User Agent</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($logs as $log): ?>
            <tr>
                <td>
                    <div class="d-flex flex-column">
                        <span class="small fw-bold"><?= date('d M Y', strtotime($log['created_at'])) ?></span>
                        <small class="text-white-50" style="font-size: 0.75rem;"><?= date('H:i', strtotime($log['created_at'])) ?></small>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <span class="small fw-bold"><?= esc($log['nama_lengkap'] ?: 'System') ?></span>
                        <small class="text-white-50" style="font-size: 0.75rem;"><?= esc($log['nama_role']) ?></small>
                    </div>
                </td>
                <td><span class="badge bg-secondary" style="font-size: 0.7rem;"><?= esc($log['aksi']) ?></span></td>
                <td><span class="small"><?= esc($log['module'] ?? '-') ?></span></td>
                <td><span class="small"><?= esc($log['deskripsi']) ?></span></td>
                <td class="small">
                    <?php 
                    $metadata = $log['metadata'];
                    if (!empty($metadata)):
                        // Check if it's JSON
                        if (str_starts_with($metadata, '{') || str_starts_with($metadata, '[')) {
                            $json = json_decode($metadata, true);
                            if ($json) {
                                echo '<ul class="mb-0 ps-3 text-white-50" style="font-size: 0.75rem;">';
                                foreach ($json as $key => $val) {
                                    if (is_array($val)) $val = implode(', ', $val);
                                    echo '<li>' . esc(ucfirst($key) . ': ' . $val) . '</li>';
                                }
                                echo '</ul>';
                            } else {
                                echo esc($metadata);
                            }
                        } else {
                            // It's a plain string (maybe with pipes)
                            echo esc($metadata);
                        }
                    else:
                        echo '-';
                    endif;
                    ?>
                </td>
                <td><small class="font-monospace text-white-50" style="font-size: 0.7rem;"><?= esc($log['ip_address'] ?: '-') ?></small></td>
                <td>
                    <div class="text-white-50 text-wrap" style="font-size: 0.7rem; line-height: 1.1;">
                        <?= esc($log['user_agent'] ?? '-') ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="d-flex justify-content-center mt-3">
    <?= $pager->links('default', 'glass_pagination') ?>
</div>
<?= $this->endSection() ?>
