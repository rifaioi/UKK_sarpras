<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateJenisKerusakanTable extends Migration
{
    public function up()
    {
        // 1. Create jenis_kerusakan table
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_jenis' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
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
        $this->forge->createTable('jenis_kerusakan');

        // 2. Add jenis_kerusakan_id to pengembalian
        $this->forge->addColumn('pengembalian', [
            'jenis_kerusakan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'kondisi_id',
            ],
        ]);

        // 3. Add jenis_kerusakan_id to pengaduan
        $this->forge->addColumn('pengaduan', [
            'jenis_kerusakan_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'lokasi',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pengembalian', 'jenis_kerusakan_id');
        $this->forge->dropColumn('pengaduan', 'jenis_kerusakan_id');
        $this->forge->dropTable('jenis_kerusakan');
    }
}
