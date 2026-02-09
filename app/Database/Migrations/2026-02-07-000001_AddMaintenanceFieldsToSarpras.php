<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMaintenanceFieldsToSarpras extends Migration
{
    public function up()
    {
        // Add maintenance fields directly to sarpras table
        $this->forge->addColumn('sarpras', [
            'maintenance_interval' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'default' => null,
                'comment' => 'Maintenance interval in months (3, 6, 12, etc.)',
                'after' => 'status',
            ],
            'last_maintenance_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Last maintenance performed',
                'after' => 'maintenance_interval',
            ],
            'next_maintenance_date' => [
                'type' => 'DATE',
                'null' => true,
                'comment' => 'Next maintenance due date',
                'after' => 'last_maintenance_date',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('sarpras', ['maintenance_interval', 'last_maintenance_date', 'next_maintenance_date']);
    }
}
