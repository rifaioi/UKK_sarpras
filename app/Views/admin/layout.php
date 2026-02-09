<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('page_title') ?? 'Sarpras' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=1.0') ?>">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column px-2 py-3 text-white">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none border-bottom border-secondary pb-3 w-100">
                <span class="fs-5 fw-bold text-primary">Sarpras</span>
            </a>
            
            <ul class="nav nav-pills flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (uri_string() == 'dashboard' || uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <!-- 1. MANAJEMEN SISTEM -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Sistem</span>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/users') ?>" class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
                        <i class="bi bi-people me-2"></i> Manajemen User
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/log') ?>" class="nav-link <?= strpos(uri_string(), 'admin/log') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clock-history me-2"></i> Log Aktivitas
                    </a>
                </li>

                <!-- 2. SETUP & INVENTORY -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Inventory Setup</span>
                </li>
                <li>
                    <a href="<?= base_url('admin/sarpras') ?>" class="nav-link <?= strpos(uri_string(), 'admin/sarpras') !== false ? 'active' : '' ?>">
                        <i class="bi bi-box-seam me-2"></i> Data Sarpras
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/categories') ?>" class="nav-link <?= strpos(uri_string(), 'admin/categories') !== false ? 'active' : '' ?>">
                        <i class="bi bi-tags me-2"></i> Kategori & Lifespan
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/locations') ?>" class="nav-link <?= strpos(uri_string(), 'admin/locations') !== false ? 'active' : '' ?>">
                        <i class="bi bi-geo-alt me-2"></i> Lokasi Barang
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/inspection-templates') ?>" class="nav-link <?= strpos(uri_string(), 'admin/inspection-templates') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clipboard-check me-2"></i> Template Inspeksi
                    </a>
                </li>

                <!-- 3. OPERASIONAL (WORKFLOW) -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Operasional</span>
                </li>
                <li>
                    <a href="<?= base_url('admin/peminjaman') ?>" class="nav-link <?= strpos(uri_string(), 'admin/peminjaman') !== false ? 'active' : '' ?>">
                        <i class="bi bi-cart-check me-2"></i> Peminjaman
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pengembalian') ?>" class="nav-link <?= (strpos(uri_string(), 'admin/pengembalian') !== false && strpos(uri_string(), 'admin/pengembalian/rusak') === false) ? 'active' : '' ?>">
                        <i class="bi bi-arrow-return-left me-2"></i> Pengembalian
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pengaduan') ?>" class="nav-link <?= strpos(uri_string(), 'admin/pengaduan') !== false ? 'active' : '' ?>">
                        <i class="bi bi-exclamation-triangle me-2"></i> Pengaduan Member
                    </a>
                </li>

                <!-- 4. PEMELIHARAAN -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Maintenance</span>
                </li>
                <li>
                    <a href="<?= base_url('admin/pengembalian/rusak') ?>" class="nav-link <?= strpos(uri_string(), 'admin/pengembalian/rusak') !== false ? 'active' : '' ?>">
                        <i class="bi bi-tools me-2"></i> Perlu Perbaikan
                    </a>
                </li>
                <li>
                    <?php $maintActive = strpos(uri_string(), 'admin/maintenance') !== false; ?>
                    <a href="#maintenanceMenu" class="nav-link d-flex align-items-center <?= $maintActive ? 'active' : '' ?>" data-bs-toggle="collapse" role="button">
                        <i class="bi bi-wrench-adjustable me-2"></i> Pemeliharaan <i class="bi bi-chevron-down ms-auto small"></i>
                    </a>
                    <div class="collapse <?= $maintActive ? 'show' : '' ?>" id="maintenanceMenu">
                        <ul class="nav nav-pills flex-column ps-3 mt-1" style="font-size: 0.85rem;">
                            <li><a href="<?= base_url('admin/maintenance/priority') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'maintenance/priority') !== false ? 'fw-bold text-primary' : '' ?>">Priority Dash</a></li>
                            <li><a href="<?= base_url('admin/maintenance/frequent-damage') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'maintenance/frequent-damage') !== false ? 'fw-bold text-primary' : '' ?>">Unit Sering Rusak</a></li>
                            <li><a href="<?= base_url('admin/maintenance/upcoming') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'maintenance/upcoming') !== false ? 'fw-bold text-primary' : '' ?>">Upcoming</a></li>
                            <li><a href="<?= base_url('admin/maintenance/records') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'maintenance/records') !== false ? 'fw-bold text-primary' : '' ?>">Riwayat</a></li>
                        </ul>
                    </div>
                </li>

                <!-- 5. INSIGHTS -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Laporan & Analitik</span>
                </li>
                <li>
                    <a href="<?= base_url('admin/analytics') ?>" class="nav-link <?= strpos(uri_string(), 'admin/analytics') !== false ? 'active' : '' ?>">
                        <i class="bi bi-bar-chart-line me-2"></i> Advanced Analytics
                    </a>
                </li>
                <li>
                    <?php $repActive = strpos(uri_string(), 'reports/') !== false; ?>
                    <a href="#laporanMenu" class="nav-link d-flex align-items-center <?= $repActive ? 'active' : '' ?>" data-bs-toggle="collapse" role="button">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Cetak Laporan <i class="bi bi-chevron-down ms-auto small"></i>
                    </a>
                    <div class="collapse <?= $repActive ? 'show' : '' ?>" id="laporanMenu">
                        <ul class="nav nav-pills flex-column ps-3 mt-1" style="font-size: 0.85rem;">
                            <li><a href="<?= base_url('admin/reports/peminjaman') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'reports/peminjaman') !== false ? 'fw-bold text-primary' : '' ?>">Lap. Peminjaman</a></li>
                            <li><a href="<?= base_url('admin/reports/pengaduan') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'reports/pengaduan') !== false ? 'fw-bold text-primary' : '' ?>">Lap. Pengaduan</a></li>
                            <li><a href="<?= base_url('admin/reports/asset-health') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'reports/asset-health') !== false ? 'fw-bold text-primary' : '' ?>">Kesehatan Aset</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>

        <!-- Main Content Wrapper -->
        <div class="content-wrapper d-flex flex-column">
            
            <!-- Topbar -->
            <div class="topbar">
                <div class="user-menu border-start ps-3">
                    <div class="user-info d-none d-md-block me-2 text-end">
                        <div class="fw-bold small lh-1 mb-1"><?= session()->get('nama') ?></div>
                        <span class="role-badge">Administrator</span>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-3 text-secondary ms-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="<?= base_url('admin/profile') ?>">Profil Saya</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Page Content -->
            <div class="page-content">
                <?php if (session()->getFlashdata('success')) : ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('errors')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar Scroll Persistence
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const scrollPos = sessionStorage.getItem('sidebar_scroll_admin');
            if (scrollPos) {
                sidebar.scrollTop = scrollPos;
            }

            // Save position before page unloads or when clicking links
            const saveScroll = () => {
                sessionStorage.setItem('sidebar_scroll_admin', sidebar.scrollTop);
            };

            sidebar.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', saveScroll);
            });
            window.addEventListener('beforeunload', saveScroll);
        });

        // Global delete confirmation
        document.addEventListener('click', function(e) {
            const deleteBtn = e.target.closest('.btn-danger');
            if (deleteBtn && deleteBtn.tagName === 'A' && 
                (deleteBtn.innerText.trim().toLowerCase().includes('delete') || 
                 deleteBtn.innerText.trim().toLowerCase().includes('hapus') || 
                 deleteBtn.getAttribute('title')?.toLowerCase().includes('hapus'))) {
                
                // If it already has an onclick that returns false, we don't need to do anything 
                // but since we are removing them, this will be the primary one.
                if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>
