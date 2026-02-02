<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePengaduanTable extends Migration
{
    public function up()
    {
        $fields = [
            'sarpras_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'user_id'
            ],
            'bukti_foto' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'lokasi'
            ],
            'catatan' => [ // Catatan Admin
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status_id'
            ]
        ];

        $this->forge->addColumn('pengaduan', $fields);

        // Add foreign key for sarpras_id
        // Note: Using raw SQL for adding FK to existing table is often safer/easier in CI3/CI4 if addForeignKey/addColumn combination is tricky, 
        // but forge support addForeignKey with processIndexes usually works on createTable. 
        // For addColumn, we usually need to add the key separately or use raw SQL.
        // Let's try raw SQL to be safe and explicit.
        $this->db->query('ALTER TABLE pengaduan ADD CONSTRAINT pengaduan_sarpras_id_foreign FOREIGN KEY (sarpras_id) REFERENCES sarpras(id) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        // Drop FK first
        $this->db->query('ALTER TABLE pengaduan DROP FOREIGN KEY pengaduan_sarpras_id_foreign');
        
        $this->forge->dropColumn('pengaduan', ['sarpras_id', 'bukti_foto', 'catatan']);
    }
}
