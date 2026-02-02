<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveJenisKerusakan extends Migration
{
    public function up()
    {
        // Drop foreign keys if exists (good practice, though sometimes handled by driver)
        // We will just drop columns which should cascade or just work if no constraint name specified manually 
        // properly in previous migrations. 
        // However, CI4 dropColumn usually handles just the column.

        $this->forge->dropColumn('pengaduan', 'jenis_kerusakan_id');
        $this->forge->dropColumn('pengembalian', 'jenis_kerusakan_id');
        $this->forge->dropTable('jenis_kerusakan');
    }

    public function down()
    {
        // Re-create table
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('jenis_kerusakan');

        // Re-add columns
        $fields = [
            'jenis_kerusakan_id' => [
                'type' => 'INT',
                'constraint' => 11, 
                'unsigned' => true, 
                'null' => true
            ]
        ];
        $this->forge->addColumn('pengembalian', $fields);
        $this->forge->addColumn('pengaduan', $fields);
    }
}
