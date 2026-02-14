<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddModuleAndUserAgentToActivityLog extends Migration
{
    public function up()
    {
        $fields = [
            'module' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'null'       => true, // Temporarily nullable to avoid issues with existing data
                'after'      => 'aksi',
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'ip_address',
            ],
        ];
        $this->forge->addColumn('activity_log', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_log', 'module');
        $this->forge->dropColumn('activity_log', 'user_agent');
    }
}
