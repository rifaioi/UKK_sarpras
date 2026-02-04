<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('logout', 'Auth::logout');
$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);

$routes->group('admin', ['filter' => 'admin'], function($routes) {
    // Users
    $routes->get('users', 'Admin\Users::index');
    $routes->get('users/create', 'Admin\Users::create');
    $routes->post('users/store', 'Admin\Users::store');
    $routes->get('users/edit/(:num)', 'Admin\Users::edit/$1');
    $routes->post('users/update/(:num)', 'Admin\Users::update/$1');
    $routes->get('users/delete/(:num)', 'Admin\Users::delete/$1');
    
    // Locations
    $routes->get('locations', 'Admin\Locations::index');
    $routes->get('locations/create', 'Admin\Locations::create');
    $routes->post('locations/store', 'Admin\Locations::store');
    $routes->get('locations/edit/(:num)', 'Admin\Locations::edit/$1');
    $routes->post('locations/update/(:num)', 'Admin\Locations::update/$1');
    $routes->get('locations/delete/(:num)', 'Admin\Locations::delete/$1');
    
    // Categories
    $routes->get('categories', 'Admin\Categories::index');
    $routes->get('categories/create', 'Admin\Categories::create');
    $routes->post('categories/store', 'Admin\Categories::store');
    $routes->get('categories/edit/(:num)', 'Admin\Categories::edit/$1');
    $routes->post('categories/update/(:num)', 'Admin\Categories::update/$1');
    $routes->get('categories/delete/(:num)', 'Admin\Categories::delete/$1');
    
    // Sarpras
    $routes->get('sarpras', 'Admin\Sarpras::index');
    $routes->get('sarpras/create', 'Admin\Sarpras::create');
    $routes->get('sarpras/create/(:num)', 'Admin\Sarpras::create/$1');
    $routes->post('sarpras/store', 'Admin\Sarpras::store');
    $routes->get('sarpras/edit/(:num)', 'Admin\Sarpras::edit/$1');
    $routes->post('sarpras/update/(:num)', 'Admin\Sarpras::update/$1');
    $routes->get('sarpras/units', 'Admin\Sarpras::units');
    $routes->get('sarpras/show/(:num)', 'Admin\Sarpras::show/$1');
    $routes->get('sarpras/delete/(:num)', 'Admin\Sarpras::delete/$1');

    // Admin Peminjaman
    // Admin Peminjaman
    $routes->get('sarpras/delete_group/(:num)', 'Admin\Sarpras::delete_group/$1');
    $routes->get('peminjaman', 'Admin\Peminjaman::index');
    $routes->get('peminjaman/approve/(:num)', 'Admin\Peminjaman::approve/$1');
    $routes->post('peminjaman/reject/(:num)', 'Admin\Peminjaman::reject/$1');
    $routes->get('peminjaman/print/(:num)', 'Admin\Peminjaman::print/$1');
    $routes->get('peminjaman/delete/(:num)', 'Admin\Peminjaman::delete/$1');
    $routes->get('peminjaman/archived', 'Admin\Peminjaman::archived');
    $routes->get('peminjaman/restore/(:num)', 'Admin\Peminjaman::restore/$1');

    // Admin Pengembalian
    $routes->get('kembali', 'Admin\Pengembalian::index');
    $routes->get('kembali/process/(:num)', 'Admin\Pengembalian::form/$1');
    $routes->post('kembali/store', 'Admin\Pengembalian::store');
    $routes->get('pengembalian', 'Admin\Pengembalian::index');
    $routes->get('pengembalian/riwayat', 'Admin\Pengembalian::riwayat');
    
    // Sedang Dalam Perbaikan Management
    $routes->get('pengembalian/rusak', 'Admin\Pengembalian::rusak');
    $routes->get('pengembalian/restock/(:num)', 'Admin\Pengembalian::restock/$1');
    $routes->get('pengembalian/scrap/(:num)', 'Admin\Pengembalian::scrap/$1');
    
    $routes->get('pengembalian/detail/(:num)', 'Admin\Pengembalian::detail/$1');
    $routes->get('pengembalian/process/(:num)', 'Admin\Pengembalian::form/$1');
    $routes->post('pengembalian/store', 'Admin\Pengembalian::store');

    // Admin Pengaduan
    $routes->get('pengaduan', 'Admin\Pengaduan::index');
    $routes->get('pengaduan/process/(:num)', 'Admin\Pengaduan::process/$1');
    $routes->get('pengaduan/complete/(:num)', 'Admin\Pengaduan::complete/$1');
    $routes->post('pengaduan/update_status', 'Admin\Pengaduan::update_status');
    $routes->get('pengaduan/delete/(:num)', 'Admin\Pengaduan::delete/$1');

    // Activity Log
    $routes->get('log', 'Admin\ActivityLog::index');
    
    // Admin Profile
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');
    $routes->post('profile/update_password', 'Admin\Profile::update_password');
    
    // Reports
    $routes->get('reports/peminjaman', 'Admin\Laporan::peminjaman');
    $routes->get('reports/pengaduan', 'Admin\Laporan::pengaduan');
    $routes->get('reports/asset-health', 'Admin\Laporan::asset_health');
});

$routes->group('member', ['filter' => 'member'], function($routes) {
    $routes->get('dashboard', 'Member\Dashboard::index');
    $routes->get('items', 'Member\Items::index');
    $routes->get('history', 'Member\History::index');
    $routes->get('borrow/(:num)', 'Member\Dashboard::borrow/$1');
    $routes->post('store_borrow', 'Member\Dashboard::store_borrow');
    $routes->get('borrow/cancel/(:num)', 'Member\Dashboard::cancel/$1');
    // Pengaduan Member
    $routes->get('pengaduan', 'Member\Pengaduan::create'); 
    $routes->post('pengaduan/store', 'Member\Pengaduan::store');

    // Profil & Ubah Password
    $routes->get('profile', 'Member\Profile::index');
    $routes->post('profile/update', 'Member\Profile::update');
    $routes->post('profile/update_password', 'Member\Profile::update_password');
});

$routes->group('petugas', ['filter' => 'petugas'], function($routes) {
    $routes->get('dashboard', 'Petugas\Dashboard::index');
    
    // Read Only Sarpras
    $routes->get('sarpras', 'Petugas\Sarpras::index');
    $routes->get('sarpras/units', 'Petugas\Sarpras::units');
    $routes->get('sarpras/show/(:num)', 'Petugas\Sarpras::show/$1');
    
    // Peminjaman
    $routes->get('peminjaman', 'Petugas\Peminjaman::index');
    $routes->get('peminjaman/approve/(:num)', 'Petugas\Peminjaman::approve/$1');
    $routes->get('peminjaman/reject/(:num)', 'Petugas\Peminjaman::reject/$1');
    
    // Pengembalian
    $routes->get('pengembalian', 'Petugas\Pengembalian::index');
    $routes->get('pengembalian/process/(:num)', 'Petugas\Pengembalian::form/$1');
    $routes->post('pengembalian/store', 'Petugas\Pengembalian::store');
    $routes->get('pengembalian/riwayat', 'Petugas\Pengembalian::riwayat');
    $routes->get('pengembalian/rusak', 'Petugas\Pengembalian::rusak');
    $routes->get('pengembalian/scan', 'Petugas\Pengembalian::scan');
    $routes->get('pengembalian/detail/(:num)', 'Petugas\Pengembalian::detail/$1');
    
    // Pengaduan
    $routes->get('pengaduan', 'Petugas\Pengaduan::index');
    $routes->get('pengaduan/process/(:num)', 'Petugas\Pengaduan::process/$1');
    $routes->get('pengaduan/complete/(:num)', 'Petugas\Pengaduan::complete/$1');
    $routes->post('pengaduan/update_status', 'Petugas\Pengaduan::update_status');
    $routes->get('pengaduan/delete/(:num)', 'Petugas\Pengaduan::delete/$1');
    
    // Petugas Profile
    $routes->get('profile', 'Petugas\Profile::index');
    $routes->post('profile/update', 'Petugas\Profile::update');
    $routes->post('profile/update_password', 'Petugas\Profile::update_password');

});
