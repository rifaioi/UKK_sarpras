<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMaintenanceTables extends Migration
{
    public function up()
    {
        // 1. Maintenance Schedules
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'sarpras_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'scheduled_date' => ['type' => 'DATE'],
            'maintenance_type' => ['type' => 'VARCHAR', 'constraint' => 100], // Rutin, Perbaikan, dll
            'action_type' => ['type' => 'VARCHAR', 'constraint' => 100], // Pembersihan, Pengecekan, dll
            'technician' => ['type' => 'VARCHAR', 'constraint' => 150],
            'description' => ['type' => 'TEXT', 'null' => true],
            'status' => ['type' => 'ENUM', 'constraint' => ['Scheduled', 'Delayed', 'Canceled', 'Completed'], 'default' => 'Scheduled'],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sarpras_id', 'sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_schedules');

        // 2. Maintenance Records
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'schedule_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'completion_date' => ['type' => 'DATE'],
            'result' => ['type' => 'VARCHAR', 'constraint' => 100], // Sesuai screenshot: Baik, dll
            'condition_after' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'cost' => ['type' => 'DECIMAL', 'constraint' => '15,2', 'default' => 0.00],
            'notes' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('schedule_id', 'maintenance_schedules', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('condition_after', 'kondisi_alat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_records');

        // 3. Add columns to Sarpras table
        $fields = [];
        if (!$this->db->fieldExists('maintenance_interval', 'sarpras')) {
            $fields['maintenance_interval'] = [
                'type' => 'INT',
                'constraint' => 11,
                'default' => 0,
                'null' => true,
                'comment' => 'Interval in days'
            ];
        }
        if (!$this->db->fieldExists('last_maintenance_date', 'sarpras')) {
            $fields['last_maintenance_date'] = ['type' => 'DATE', 'null' => true];
        }
        if (!$this->db->fieldExists('next_maintenance_date', 'sarpras')) {
            $fields['next_maintenance_date'] = ['type' => 'DATE', 'null' => true];
        }

        if (!empty($fields)) {
            $this->forge->addColumn('sarpras', $fields);
        }
    }

    public function down()
    {
        $this->forge->dropTable('maintenance_records');
        $this->forge->dropTable('maintenance_schedules');
        $this->forge->dropColumn('sarpras', ['maintenance_interval', 'last_maintenance_date', 'next_maintenance_date']);
    }
}
