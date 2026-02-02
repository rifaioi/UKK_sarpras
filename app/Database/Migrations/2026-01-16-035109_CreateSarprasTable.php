<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSarprasTable extends Migration
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
            'kode' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'unique'     => true,
            ],
            'nama' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'kategori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'location_id' => [  // Changed from 'lokasi' varchar to location_id INT FK
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'stok' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'kondisi_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'is_deleted' => [
                'type'    => 'TINYINT',
                'default' => 0,
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
        $this->forge->addForeignKey('kategori_id', 'kategori_sarpras', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('kondisi_id', 'kondisi_alat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('sarpras');
    }

    public function down()
    {
        $this->forge->dropTable('sarpras');
    }
}
