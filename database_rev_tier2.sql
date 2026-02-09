-- Tier 2 Full Checklist Revision SQL (Safe Mode: only new/replaced inspection tables)
DROP TABLE IF EXISTS inspeksi_checklist;
DROP TABLE IF EXISTS inspeksi_pinjam;
DROP TABLE IF EXISTS inspection_template_items;

-- 1. Management Template Checklist (Admin)
CREATE TABLE inspection_template_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    kategori_id INT NOT NULL,
    nama_item VARCHAR(255) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    deleted_at DATETIME NULL
);

-- 2. Header Inspeksi (Transaction)
CREATE TABLE inspeksi_pinjam (
    id INT PRIMARY KEY AUTO_INCREMENT,
    peminjaman_id INT NOT NULL,
    inspector_id INT NOT NULL,
    type ENUM('keluar', 'kembali') NOT NULL,
    inspection_date DATETIME NOT NULL,
    photo_evidence VARCHAR(255) NULL,
    notes TEXT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 3. Detail Checklist Hasil Inspeksi
CREATE TABLE inspeksi_checklist (
    id INT PRIMARY KEY AUTO_INCREMENT,
    inspection_id INT NOT NULL,
    checklist_item_id INT NOT NULL,
    status ENUM('ok', 'damaged', 'missing', 'n/a') NOT NULL,
    description VARCHAR(255) NULL
);
