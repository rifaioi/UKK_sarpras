<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RenameNamaBarangToNama extends Migration
{
    public function up()
    {
        $fields = [
            'nama_barang' => [
                'name'       => 'nama',
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
        ];
        $this->forge->modifyColumn('sarpras', $fields);
    }

    public function down()
    {
        $fields = [
            'nama' => [
                'name'       => 'nama_barang',
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
        ];
        $this->forge->modifyColumn('sarpras', $fields);
    }
}
