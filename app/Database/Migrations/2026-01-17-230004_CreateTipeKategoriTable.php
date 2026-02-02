<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTipeKategoriTable extends Migration
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
            'nama_tipe' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tipe_kategori');
    }

    public function down()
    {
        $this->forge->dropTable('tipe_kategori');
    }
}
