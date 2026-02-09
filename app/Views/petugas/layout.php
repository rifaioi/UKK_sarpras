<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('page_title') ?? 'Petugas - Sarpras' ?></title>
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
                    <a href="<?= base_url('petugas/dashboard') ?>" class="nav-link <?= uri_string() == 'petugas/dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <!-- 1. MANAJEMEN BARANG -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Inventory</span>
                </li>
                <li>
                    <a href="<?= base_url('petugas/sarpras') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/sarpras') !== false ? 'active' : '' ?>">
                        <i class="bi bi-box-seam me-2"></i> Cek Stok & Daftar Unit
                    </a>
                </li>

                <!-- 2. WORKFLOW OPERASIONAL -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Operasional</span>
                </li>
                <li>
                    <a href="<?= base_url('petugas/peminjaman') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/peminjaman') !== false ? 'active' : '' ?>">
                        <i class="bi bi-cart-check me-2"></i> Persetujuan Pinjam
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('petugas/pengembalian') ?>" class="nav-link <?= (strpos(uri_string(), 'petugas/pengembalian') !== false && strpos(uri_string(), 'petugas/pengembalian/rusak') === false) ? 'active' : '' ?>">
                        <i class="bi bi-arrow-return-left me-2"></i> Proses Kembali
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('petugas/pengaduan') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/pengaduan') !== false ? 'active' : '' ?>">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Laporan Pengaduan
                    </a>
                </li>

                <!-- 3. PEMELIHARAAN -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Maintenance</span>
                </li>
                <li>
                    <a href="<?= base_url('petugas/pengembalian/rusak') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/pengembalian/rusak') !== false ? 'active' : '' ?>">
                        <i class="bi bi-wrench-adjustable me-2"></i> Sedang Perbaikan
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('petugas/maintenance/upcoming') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/maintenance/upcoming') !== false ? 'active' : '' ?>">
                        <i class="bi bi-calendar-event me-2"></i> Jadwal Mendatang
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('petugas/maintenance/frequent-damage') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/maintenance/frequent-damage') !== false ? 'active' : '' ?>">
                        <i class="bi bi-bar-chart-steps me-2"></i> Unit Sering Rusak
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('petugas/maintenance/records') ?>" class="nav-link <?= strpos(uri_string(), 'petugas/maintenance/records') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clock-history me-2"></i> Riwayat Servis
                    </a>
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
                        <span class="role-badge">Petugas</span>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-3 text-secondary ms-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="<?= base_url('petugas/profile') ?>">Profil Saya</a></li>
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
            const scrollPos = sessionStorage.getItem('sidebar_scroll_petugas');
            if (scrollPos) {
                sidebar.scrollTop = scrollPos;
            }

            const saveScroll = () => {
                sessionStorage.setItem('sidebar_scroll_petugas', sidebar.scrollTop);
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
                
                if (!confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                    e.preventDefault();
                }
            }
        });
    </script>
</body>
</html>
