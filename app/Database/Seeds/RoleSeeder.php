<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncate
        $this->db->disableForeignKeyChecks();
        $this->db->table('roles')->truncate();
        $this->db->enableForeignKeyChecks();

        $data = [
            ['nama_role' => 'Admin'],
            ['nama_role' => 'Petugas'],
            ['nama_role' => 'Peminjam'], // or 'User' or 'Pengguna Umum'
        ];

        // Using Query Builder
        $this->db->table('roles')->insertBatch($data);
    }
}
