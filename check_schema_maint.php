<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ukk_sarpras';
$conn = new mysqli($host, $user, $pass, $db);

function describe($conn, $table) {
    echo "\n--- $table ---\n";
    $res = $conn->query("DESCRIBE $table");
    while($row = $res->fetch_assoc()) {
        echo "{$row['Field']} ({$row['Type']})\n";
    }
}

describe($conn, 'sarpras');
describe($conn, 'maintenance_records');
describe($conn, 'maintenance_schedules');
