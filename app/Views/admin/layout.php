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
        <div class="sidebar d-flex flex-column px-2 py-3 text-white" style="width: 210px;">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none border-bottom border-secondary pb-3 w-100">
                <span class="fs-4"> Sarpras</span>
            </a>
            
            <ul class="nav nav-pills flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= (uri_string() == 'dashboard' || uri_string() == 'admin/dashboard') ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('admin/users') ?>" class="nav-link <?= strpos(uri_string(), 'admin/users') !== false ? 'active' : '' ?>">
                        <i class="bi bi-people me-2"></i> Manajemen User
                    </a>
                </li>

                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Master Data</span>
                </li>
                <li>
                    <a href="<?= base_url('admin/categories') ?>" class="nav-link <?= strpos(uri_string(), 'admin/categories') !== false ? 'active' : '' ?>">
                        <i class="bi bi-tags me-2"></i> Data Kategori
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/locations') ?>" class="nav-link <?= strpos(uri_string(), 'admin/locations') !== false ? 'active' : '' ?>">
                        <i class="bi bi-geo-alt me-2"></i> Data Lokasi
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/sarpras') ?>" class="nav-link <?= strpos(uri_string(), 'admin/sarpras') !== false ? 'active' : '' ?>">
                        <i class="bi bi-box-seam me-2"></i> Data Sarpras
                    </a>
                </li>

                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Transaksi</span>
                </li>
                <li>
                    <a href="<?= base_url('admin/peminjaman') ?>" class="nav-link <?= strpos(uri_string(), 'admin/peminjaman') !== false ? 'active' : '' ?>">
                        <i class="bi bi-cart me-2"></i> Peminjaman
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/kembali') ?>" class="nav-link <?= strpos(uri_string(), 'admin/kembali') !== false ? 'active' : '' ?>">
                        <i class="bi bi-arrow-return-left me-2"></i> Pengembalian
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pengembalian/rusak') ?>" class="nav-link <?= strpos(uri_string(), 'admin/pengembalian/rusak') !== false ? 'active' : '' ?>">
                        <i class="bi bi-tools me-2"></i> Sedang Perbaikan
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('admin/pengaduan') ?>" class="nav-link <?= strpos(uri_string(), 'admin/pengaduan') !== false ? 'active' : '' ?>">
                        <i class="bi bi-exclamation-triangle me-2"></i> Pengaduan
                    </a>
                </li>

                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Laporan & Sistem</span>
                </li>
                <li>
                    <a href="#laporanMenu" class="nav-link d-flex align-items-center <?= strpos(uri_string(), 'reports/') !== false ? 'active' : '' ?>" data-bs-toggle="collapse" role="button" aria-expanded="<?= strpos(uri_string(), 'reports/') !== false ? 'true' : 'false' ?>">
                        <i class="bi bi-file-earmark-bar-graph me-2"></i> Laporan <i class="bi bi-chevron-down ms-auto small"></i>
                    </a>
                    <div class="collapse <?= strpos(uri_string(), 'reports/') !== false ? 'show' : '' ?>" id="laporanMenu">
                        <ul class="nav nav-pills flex-column ps-3 mt-1" style="font-size: 0.85rem;">
                            <li>
                                <a href="<?= base_url('admin/reports/peminjaman') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'reports/peminjaman') !== false ? 'fw-bold text-primary' : '' ?>">
                                     Peminjaman
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('admin/reports/pengaduan') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'reports/pengaduan') !== false ? 'fw-bold text-primary' : '' ?>">
                                     Pengaduan
                                </a>
                            </li>
                            <li>
                                <a href="<?= base_url('admin/reports/asset-health') ?>" class="nav-link py-1 <?= strpos(uri_string(), 'reports/asset-health') !== false ? 'fw-bold text-primary' : '' ?>">
                                     Kesehatan Aset
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li>
                    <a href="<?= base_url('admin/log') ?>" class="nav-link <?= strpos(uri_string(), 'admin/log') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clock-history me-2"></i> Log Aktivitas
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content Wrapper -->
        <div class="content-wrapper d-flex flex-column" style="width: 100%;">
            
            <!-- Topbar -->
            <div class="topbar">
                <div class="user-menu border-start ps-3">
                    <div class="user-info d-none d-md-block">
                        <strong><?= session()->get('nama') ?></strong>
                        <small>Administrator</small>
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
</body>
</html>
