<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'ukk_sarpras';
$conn = new mysqli($host, $user, $pass, $db);

$sql = "ALTER TABLE maintenance_records 
        ADD COLUMN new_kondisi_id INT UNSIGNED DEFAULT NULL AFTER cost,
        ADD COLUMN status ENUM('Rencana', 'Selesai') DEFAULT 'Selesai' AFTER new_kondisi_id";

if ($conn->query($sql)) {
    echo "Maintenance table updated successfully.\n";
} else {
    echo "Notice: " . $conn->error . "\n";
}

$sqlReminder = "CREATE TABLE IF NOT EXISTS maintenance_reminders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sarpras_id INT UNSIGNED NOT NULL,
    reminder_date DATE NOT NULL,
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sqlReminder)) {
    echo "Reminder table ready.\n";
} else {
    echo "Error: " . $conn->error . "\n";
}
