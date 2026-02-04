<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKodePeminjamanToPeminjaman extends Migration
{
    public function up()
    {
        $this->forge->addColumn('peminjaman', [
            'kode_peminjaman' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'after'      => 'id',
                'null'       => true,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('peminjaman', 'kode_peminjaman');
    }
}
