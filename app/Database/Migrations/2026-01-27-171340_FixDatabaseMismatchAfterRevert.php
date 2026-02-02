<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixDatabaseMismatchAfterRevert extends Migration
{
    public function up()
    {
        $db = \Config\Database::connect();

        // 1. Add parent_id back to sarpras if missing
        if (!$db->fieldExists('parent_id', 'sarpras')) {
            $this->forge->addColumn('sarpras', [
                'parent_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'id',
                ],
            ]);
        }

        // 2. Add kategori_id back to sarpras if missing
        if (!$db->fieldExists('kategori_id', 'sarpras')) {
            $this->forge->addColumn('sarpras', [
                'kategori_id' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                    'after'      => 'nama',
                ],
            ]);
        }

        // 3. Drop alat_id if exists
        if ($db->fieldExists('alat_id', 'sarpras')) {
            $this->forge->dropColumn('sarpras', 'alat_id');
        }

        // 4. Drop alat table if exists
        $this->forge->dropTable('alat', true);
    }

    public function down()
    {
        // No need to implement rollback here since we are fixing a mismatch
    }
}
