<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class JenisKerusakanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_jenis' => 'Elektronik'],
            ['nama_jenis' => 'Fisik'],
            ['nama_jenis' => 'Mekanis'],
            ['nama_jenis' => 'Kosmetik (Lecet/Gores)'],
            ['nama_jenis' => 'Software/Sistem'],
            ['nama_jenis' => 'Lainnya'],
        ];

        // Using Query Builder
        $this->db->table('jenis_kerusakan')->insertBatch($data);
    }
}
