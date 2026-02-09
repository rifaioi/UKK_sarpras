<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMaintenanceTables extends Migration
{
    public function up()
    {
        // Table: maintenance_schedules
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'kategori_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'schedule_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'interval_months' => [
                'type' => 'INT',
                'constraint' => 11,
                'comment' => 'Maintenance interval in months (1, 3, 6, 12, etc.)',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kategori_id', 'kategori_sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_schedules');

        // Table: maintenance_records
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sarpras_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'schedule_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
                'comment' => 'FK to maintenance_schedules if following a schedule',
            ],
            'maintenance_date' => [
                'type' => 'DATE',
                'comment' => 'When maintenance was performed',
            ],
            'performed_by' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'comment' => 'Technician name or identifier',
            ],
            'description' => [
                'type' => 'TEXT',
                'comment' => 'What maintenance was done',
            ],
            'cost' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
                'comment' => 'Maintenance cost (optional)',
            ],
            'next_maintenance_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Calculated next maintenance date',
            ],
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_by' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sarpras_id', 'sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('schedule_id', 'maintenance_schedules', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_records');

        // Table: maintenance_reminders
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'sarpras_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'due_date' => [
                'type' => 'DATE',
                'comment' => 'When maintenance is due',
            ],
            'reminder_type' => [
                'type' => 'ENUM',
                'constraint' => ['H-7', 'H-3', 'H-0', 'overdue'],
                'comment' => 'Type of reminder',
            ],
            'is_read' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'comment' => 'Whether reminder has been viewed',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('sarpras_id', 'sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('maintenance_reminders');
    }

    public function down()
    {
        $this->forge->dropTable('maintenance_reminders', true);
        $this->forge->dropTable('maintenance_records', true);
        $this->forge->dropTable('maintenance_schedules', true);
    }
}
