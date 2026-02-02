<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateStatusPeminjamanTable extends Migration
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
            'nama_status' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('status_peminjaman');
    }

    public function down()
    {
        $this->forge->dropTable('status_peminjaman');
    }
}
