<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run()
    {
        $data = [
            ['nama_lokasi' => 'Gudang Utama', 'keterangan' => 'Penyimpanan utama barang'],
            ['nama_lokasi' => 'Lab Komputer', 'keterangan' => 'Laboratorium Komputer 1'],
        ];

        $this->db->table('locations')->insertBatch($data);
    }
}
