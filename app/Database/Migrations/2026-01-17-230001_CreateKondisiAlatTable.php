<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKondisiAlatTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kondisi' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kondisi_alat');
    }

    public function down()
    {
        $this->forge->dropTable('kondisi_alat');
    }
}
