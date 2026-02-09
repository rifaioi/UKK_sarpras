<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAnalyticsFields extends Migration
{
    public function up()
    {
        // 1. Add expected_lifespan to kategori_sarpras if not exists
        if (!$this->db->fieldExists('expected_lifespan', 'kategori_sarpras')) {
            $this->forge->addColumn('kategori_sarpras', [
                'expected_lifespan' => [
                    'type' => 'INT',
                    'constraint' => 5,
                    'default' => 5,
                    'null' => true,
                    'comment' => 'Estimated life in years'
                ],
            ]);
        }

        // 2. Add tgl_pengadaan and harga_beli to sarpras if not exists
        $sarprasFields = [];
        if (!$this->db->fieldExists('tgl_pengadaan', 'sarpras')) {
            $sarprasFields['tgl_pengadaan'] = [
                'type' => 'DATE',
                'null' => true,
            ];
        }
        if (!$this->db->fieldExists('harga_beli', 'sarpras')) {
            $sarprasFields['harga_beli'] = [
                'type' => 'DECIMAL',
                'constraint' => '15,2',
                'default' => 0.00,
            ];
        }

        if (!empty($sarprasFields)) {
            $this->forge->addColumn('sarpras', $sarprasFields);
        }
    }

    public function down()
    {
        $this->forge->dropColumn('kategori_sarpras', 'expected_lifespan');
        $this->forge->dropColumn('sarpras', ['tgl_pengadaan', 'harga_beli']);
    }
}
