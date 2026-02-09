<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ukk_sarpras';
$conn = new mysqli($host, $user, $pass, $db);

// Define some default items for categories
$items = [
    2 => ['Layar Tidak Pecah', 'Keyboard Berfungsi', 'Charger Lengkap', 'Baterai Normal'], // Laptop (ID: 2)
    1 => ['Lampu Terang', 'Lensa Bersih', 'Remote Ada', 'Kabel VGA/HDMI Ada'], // Proyektor (ID: 1)
];

foreach ($items as $catId => $checklist) {
    foreach ($checklist as $name) {
        $conn->query("INSERT INTO inspection_template_items (kategori_id, nama_item) VALUES ($catId, '$name')");
    }
}

echo "Successfully seeded default checklist items for Laptop and Proyektor.\n";
