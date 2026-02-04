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

if ($argc > 1) {
    $sqls = array_slice($argv, 1);
    foreach ($sqls as $sql) {
        echo "Executing: $sql\n";
        $result = $mysqli->query($sql);
        if ($result === TRUE) {
            echo "Query executed successfully.\n";
        } elseif ($result instanceof mysqli_result) {
            echo "Result:\n";
            while ($row = $result->fetch_assoc()) {
                print_r($row);
            }
        } else {
            echo "Error: " . $mysqli->error . "\n";
        }
    }
} else {
    foreach (['users', 'peminjaman', 'pengaduan', 'pengembalian', 'sarpras'] as $table) {
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
}

$mysqli->close();
?>
