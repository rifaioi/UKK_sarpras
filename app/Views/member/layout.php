<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $this->renderSection('page_title') ?? 'Member - Sarpras' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css?v=1.0') ?>">
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar d-flex flex-column px-2 py-3 text-white">
            <a href="/" class="d-flex align-items-center justify-content-center mb-3 mb-md-0 text-white text-decoration-none border-bottom border-secondary pb-3 w-100">
                <span class="fs-5 fw-bold text-primary">Sarpras</span>
            </a>
            
            <ul class="nav nav-pills flex-column mb-auto mt-3">
                <li class="nav-item">
                    <a href="<?= base_url('member/dashboard') ?>" class="nav-link <?= uri_string() == 'member/dashboard' ? 'active' : '' ?>">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                </li>

                <!-- LAYANAN UTAMA -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Layanan Sarpras</span>
                </li>
                <li class="nav-item">
                    <a href="<?= base_url('member/items') ?>" class="nav-link <?= strpos(uri_string(), 'member/items') !== false ? 'active' : '' ?>">
                        <i class="bi bi-plus-circle-dotted me-2"></i> Pinjam Barang
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('member/pengaduan') ?>" class="nav-link <?= strpos(uri_string(), 'member/pengaduan') !== false ? 'active' : '' ?>">
                        <i class="bi bi-exclamation-octagon me-2"></i> Ajukan Keluhan
                    </a>
                </li>

                <!-- TRACKING -->
                <li class="nav-item border-top border-secondary mt-2 pt-2">
                    <span class="text-secondary small text-uppercase fw-bold px-3">Monitoring</span>
                </li>
                <li class="nav-item">
                     <a href="<?= base_url('member/history') ?>" class="nav-link <?= strpos(uri_string(), 'member/history') !== false ? 'active' : '' ?>">
                        <i class="bi bi-clock-history me-2"></i> Riwayat & Status
                    </a>
                </li>
            </ul>
        </div>

        <!-- Sidebar Overlay for mobile -->
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        <!-- Main Content Wrapper -->
        <div class="content-wrapper d-flex flex-column">
            
            <!-- Topbar -->
            <div class="topbar">
                <button class="sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div class="user-menu border-start ps-3">
                    <div class="user-info d-none d-md-block me-2 text-end">
                        <div class="fw-bold small lh-1 mb-1"><?= session()->get('username') ?></div>
                        <span class="role-badge">Peminjam</span>
                    </div>
                    <div class="dropdown">
                        <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-3 text-secondary ms-2"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                            <li><a class="dropdown-item" href="<?= base_url('member/profile') ?>">Profil Saya</a></li>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Sidebar Scroll Persistence
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const scrollPos = sessionStorage.getItem('sidebar_scroll_member');
            if (scrollPos) {
                sidebar.scrollTop = scrollPos;
            }

            const saveScroll = () => {
                sessionStorage.setItem('sidebar_scroll_member', sidebar.scrollTop);
            };

            sidebar.querySelectorAll('.nav-link').forEach(link => {
                link.addEventListener('click', saveScroll);
            });
            window.addEventListener('beforeunload', saveScroll);


            // Mobile Sidebar Toggle
            const sidebarBtn = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            const toggleSidebar = () => {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
            };

            sidebarBtn.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            // Global Confirmation Dialog
            document.addEventListener('click', function(e) {
                const confirmBtn = e.target.closest('.btn-confirm');
                const deleteBtn = e.target.closest('.btn-delete');
                const logoutBtn = e.target.closest('.dropdown-item.text-danger[href*="logout"]');

                if (confirmBtn || deleteBtn) {
                    e.preventDefault();
                    const url = (confirmBtn || deleteBtn).getAttribute('href') || (confirmBtn || deleteBtn).getAttribute('data-url');
                    const title = deleteBtn ? 'Apakah Anda yakin?' : 'Konfirmasi Action';
                    const text = deleteBtn ? 'Data yang dihapus mungkin tidak dapat dikembalikan!' : 'Apakah Anda yakin ingin melanjutkan tindakan ini?';
                    const icon = deleteBtn ? 'warning' : 'question';
                    const confirmText = deleteBtn ? 'Ya, Hapus!' : 'Ya, Lanjutkan';

                    Swal.fire({
                        title: title,
                        text: text,
                        icon: icon,
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: confirmText,
                        cancelButtonText: 'Batal',
                        background: '#1e1e1e',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                }

                if (logoutBtn) {
                    e.preventDefault();
                    const url = logoutBtn.getAttribute('href');
                    Swal.fire({
                        title: 'Logout',
                        text: 'Apakah Anda yakin ingin keluar dari sistem?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
                        background: '#1e1e1e',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                }
            });

            // Global Form Confirmation
            document.addEventListener('submit', function(e) {
                const form = e.target.closest('.form-confirm');
                if (form) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Simpan',
                        text: 'Apakah Anda yakin ingin menyimpan perubahan ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Simpan!',
                        cancelButtonText: 'Batal',
                        background: '#1e1e1e',
                        color: '#fff'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    </script>
</body>
</html>
