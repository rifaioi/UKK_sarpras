-- SQL Schema for Maintenance Features (Tier 2.2)
-- Compatible with UKK Sarpras Requirements

-- Table: maintenance_schedules
-- Stores the periodic maintenance rules created by Admin
CREATE TABLE IF NOT EXISTS `maintenance_schedules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kategori_id` int(11) NOT NULL,
  `schedule_name` varchar(255) NOT NULL,
  `interval_months` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table: maintenance_records (as requested maintenance_log)
-- Stores the actual maintenance activities performed by Petugas per Unit
CREATE TABLE IF NOT EXISTS `maintenance_records` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sarpras_id` int(11) NOT NULL, -- Refers to the Unit
  `schedule_id` int(11) DEFAULT NULL,
  `maintenance_date` date NOT NULL,
  `performed_by` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `cost` decimal(15,2) DEFAULT 0.00,
  `new_kondisi_id` int(11) DEFAULT NULL, -- Updated condition after maintenance
  `status` varchar(50) DEFAULT 'Selesai',
  `created_by` int(11) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Note: 
-- 1. `kategori_id` links to `kategori_sarpras`.
-- 2. `sarpras_id` links to `sarpras` (which acts as the unit table).
-- 3. `new_kondisi_id` links to `kondisi_alat`.
