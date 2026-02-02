<?php
$hostname = 'localhost';
$username = 'root';
$password = ''; // Default Laragon password is empty
$database = 'ukk_sarpras';

$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error . "\n");
}

echo "Connected successfully to database '$database'.\n";

foreach (['users', 'peminjaman', 'pengaduan', 'pengembalian'] as $table) {
    $result = $mysqli->query("DESCRIBE $table");
    if ($result) {
        echo "Columns in '$table' table:\n";
        while ($row = $result->fetch_assoc()) {
            echo "- " . $row['Field'] . " (" . $row['Type'] . ")\n";
        }
    } else {
        echo "Error describing table $table: " . $mysqli->error . "\n";
    }
    echo "\n";
}

$mysqli->close();
?>
