-- Tier 2.2 Maintenance System Update
-- Menambahkan field yang diperlukan untuk tracking kondisi dan status maintenance

ALTER TABLE maintenance_records 
ADD COLUMN new_kondisi_id INT UNSIGNED DEFAULT NULL AFTER cost,
ADD COLUMN status ENUM('Rencana', 'Selesai') DEFAULT 'Selesai' AFTER new_kondisi_id;

-- Menambahkan tabel reminder jika belum ada (Opsional untuk reminder logic)
CREATE TABLE IF NOT EXISTS maintenance_reminders (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sarpras_id INT UNSIGNED NOT NULL,
    reminder_date DATE NOT NULL,
    message TEXT,
    is_read TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);
