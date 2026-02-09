<?php
// Simple script to run the revision SQL
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

$db = \Config\Database::connect();
$sql = file_get_contents('database_rev_tier2.sql');

$queries = explode(';', $sql);
foreach ($queries as $query) {
    if (trim($query)) {
        try {
            $db->query($query);
        } catch (\Exception $e) {
            echo "Error executing query: " . $e->getMessage() . "\n";
        }
    }
}
echo "Database refactored successfully.\n";
