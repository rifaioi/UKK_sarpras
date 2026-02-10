<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIpAndMetadataToActivityLog extends Migration
{
    public function up()
    {
        $fields = [
            'ip_address' => [
                'type' => 'VARCHAR',
                'constraint' => 45,
                'null' => true,
                'after' => 'deskripsi'
            ],
            'metadata' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'ip_address'
            ],
        ];
        $this->forge->addColumn('activity_log', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('activity_log', 'ip_address');
        $this->forge->dropColumn('activity_log', 'metadata');
    }
}
