<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RefineSoftDeleteAndReturn extends Migration
{
    public function up()
    {
        // Add deleted_at to users
        if (!$this->db->fieldExists('deleted_at', 'users')) {
            $this->forge->addColumn('users', [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'updated_at',
                ],
            ]);
        }

        // Add deleted_at to sarpras
        if (!$this->db->fieldExists('deleted_at', 'sarpras')) {
            $this->forge->addColumn('sarpras', [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'updated_at',
                ],
            ]);
        }

        // Add deleted_at to pengaduan
        if (!$this->db->fieldExists('deleted_at', 'pengaduan')) {
            $this->forge->addColumn('pengaduan', [
                'deleted_at' => [
                    'type' => 'DATETIME',
                    'null' => true,
                    'after' => 'updated_at',
                ],
            ]);
        }
        
        // Ensure photo column exists
        if (!$this->db->fieldExists('foto', 'pengembalian')) {
            $this->forge->addColumn('pengembalian', [
                'foto' => [
                    'type' => 'VARCHAR',
                    'constraint' => '255',
                    'null' => true,
                    'after' => 'deskripsi',
                ],
            ]);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'deleted_at');
        $this->forge->dropColumn('sarpras', 'deleted_at');
        $this->forge->dropColumn('pengaduan', 'deleted_at');
        // We usually don't drop 'foto' here as it was from a separate migration, 
        // but for this "Phase II" migration logic we follow 'up'
    }
}
