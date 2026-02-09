<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$tables = $db->listTables();
$required = ['inspection_template_items', 'inspeksi_pinjam', 'inspeksi_checklist'];

foreach ($required as $table) {
    if (in_array($table, $tables)) {
        echo "Table $table: OK\n";
    } else {
        echo "Table $table: MISSING\n";
    }
}
