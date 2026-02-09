<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInspectionTables extends Migration
{
    public function up()
    {
        // 1. Checklist Items Template (Managed by Admin)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kategori_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nama_item' => ['type' => 'VARCHAR', 'constraint' => '255'], // e.g. "Screen Condition", "Charger Included"
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori_sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inspection_checklist_items');

        // 2. Inspections (The event itself)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'peminjaman_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'inspector_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true], // Petugas doing the inspection
            'type' => ['type' => 'ENUM', 'constraint' => ['pre-borrow', 'post-return']],
            'inspection_date' => ['type' => 'DATETIME'],
            'photo_evidence' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true], // Path to photo
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('peminjaman_id', 'peminjaman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('inspector_id', 'users', 'id', 'NO ACTION', 'CASCADE');
        $this->forge->createTable('inspections');

        // 3. Inspection Results (Answers to checklist)
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'inspection_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'checklist_item_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['ok', 'damaged', 'missing', 'n/a']],
            'description' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true], // Details if damaged
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('inspection_id', 'inspections', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('checklist_item_id', 'inspection_checklist_items', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('inspection_results');
    }

    public function down()
    {
        $this->forge->dropTable('inspection_results');
        $this->forge->dropTable('inspections');
        $this->forge->dropTable('inspection_checklist_items');
    }
}
