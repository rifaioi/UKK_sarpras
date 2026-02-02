<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPhotoToPengembalian extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pengembalian', [
            'foto' => [
                'type' => 'VARCHAR',
                'constraint' => '255',
                'null' => true,
                'after' => 'deskripsi',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pengembalian', 'foto');
    }
}
