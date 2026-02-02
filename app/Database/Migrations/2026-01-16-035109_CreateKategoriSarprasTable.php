<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateKategoriSarprasTable extends Migration
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
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
            ],
            'tipe_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_deleted' => [
                'type'    => 'TINYINT',
                'default' => 0,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tipe_id', 'tipe_kategori', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kategori_sarpras');
    }

    public function down()
    {
        $this->forge->dropTable('kategori_sarpras');
    }
}
