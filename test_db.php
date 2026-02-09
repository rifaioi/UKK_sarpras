<?php
// Load CodeIgniter's bootstrap file
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require 'system/bootstrap.php';

use Config\Database;

try {
    $db = Database::connect();
    echo "Connected to DB\n";
    
    // Check Columns of inspection_checklist_items
    $query = $db->query("SHOW COLUMNS FROM inspection_checklist_items");
    $columns = $query->getResultArray();
    
    echo "Columns in inspection_checklist_items:\n";
    foreach ($columns as $col) {
        echo "- " . $col['Field'] . " (" . $col['Type'] . ")\n";
    }

} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
