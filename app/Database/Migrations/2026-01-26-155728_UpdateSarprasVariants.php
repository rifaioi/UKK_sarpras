<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateSarprasVariants extends Migration
{
    public function up()
    {
        // 1. Add parent_id
        $this->forge->addColumn('sarpras', [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);

        // 2. Add foreign key for parent_id
        $this->db->query("ALTER TABLE sarpras ADD CONSTRAINT fk_sarpras_parent FOREIGN KEY (parent_id) REFERENCES sarpras(id) ON DELETE CASCADE ON UPDATE CASCADE");

        // 3. Drop unique index on kode (it was named 'kode' in CreateSarprasTable)
        // In MySQL/MariaDB, a unique column created with 'unique => true' usually creates an index with the same name.
        $this->db->query("ALTER TABLE sarpras DROP INDEX kode");
    }

    public function down()
    {
        // 1. Restore unique index on kode
        $this->db->query("ALTER TABLE sarpras ADD UNIQUE (kode)");

        // 2. Drop foreign key
        $this->db->query("ALTER TABLE sarpras DROP FOREIGN KEY fk_sarpras_parent");

        // 3. Drop parent_id
        $this->forge->dropColumn('sarpras', 'parent_id');
    }
}
