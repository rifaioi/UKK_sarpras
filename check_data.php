<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ukk_sarpras';
$conn = new mysqli($host, $user, $pass, $db);
$res = $conn->query("SELECT COUNT(*) as total FROM inspection_template_items");
$row = $res->fetch_assoc();
echo "Total Template Items: " . $row['total'] . "\n";

$res = $conn->query("SELECT * FROM kategori_sarpras");
while($row = $res->fetch_assoc()) {
    echo "Category: " . $row['nama'] . " (ID: " . $row['id'] . ")\n";
}
