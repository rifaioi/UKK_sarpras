<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RefKategoriSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_kategori' => 'Elektronik'],
            ['nama_kategori' => 'Mebel'],
            ['nama_kategori' => 'Kebersihan'],
            ['nama_kategori' => 'Alat Peraga'],
            ['nama_kategori' => 'Lain-lain'],
        ];

        $this->db->table('ref_kategori')->insertBatch($data);
    }
}
