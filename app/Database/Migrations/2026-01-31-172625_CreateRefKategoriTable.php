<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRefKategoriTable extends Migration
{
    public function up()
    {
        // 1. Create ref_kategori table (Main Groups: Elektronik, Mebel, etc)
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'nama_kategori' => [
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
        $this->forge->createTable('ref_kategori');

        // 2. Add ref_kategori_id to kategori_sarpras (Alat level)
        $this->forge->addColumn('kategori_sarpras', [
            'ref_kategori_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'nama',
            ],
        ]);
        
        // Add FK
        $this->db->query("ALTER TABLE kategori_sarpras ADD CONSTRAINT fk_kategori_sarpras_ref FOREIGN KEY (ref_kategori_id) REFERENCES ref_kategori(id) ON DELETE SET NULL ON UPDATE CASCADE");
    }

    public function down()
    {
        $this->db->query("ALTER TABLE kategori_sarpras DROP FOREIGN KEY fk_kategori_sarpras_ref");
        $this->forge->dropColumn('kategori_sarpras', 'ref_kategori_id');
        $this->forge->dropTable('ref_kategori');
    }
}
