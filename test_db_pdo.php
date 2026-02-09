<?php
$host = 'localhost';
$db   = 'ukk_sarpras';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    
    // Check columns
    $stmt = $pdo->query("SHOW COLUMNS FROM inspection_checklist_items");
    echo "COLUMNS:\n";
    while ($row = $stmt->fetch()) {
        echo $row['Field'] . "\n";
    }

    // Check data
    $stmt = $pdo->query("SELECT * FROM inspection_checklist_items LIMIT 1");
    $data = $stmt->fetch();
    echo "\nDATA SAMPLE:\n";
    print_r($data);

} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
