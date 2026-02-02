<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            // [
            //     'username'      => 'admin',
            //     'password_hash' => password_hash('admin123', PASSWORD_BCRYPT),
            //     'nama_lengkap'  => 'Administrator',
            //     'role_id'       => 1, // Admin
            //     'created_at'    => date('Y-m-d H:i:s'),
            // ],
            // [
            //     'username'      => 'petugas',
            //     'password_hash' => password_hash('petugas123', PASSWORD_BCRYPT),
            //     'nama_lengkap'  => 'Petugas Sarpras',
            //     'role_id'       => 2, // Petugas
            //     'created_at'    => date('Y-m-d H:i:s'),
            // ],
            [
                'username'      => 'siswa',
                'password_hash' => password_hash('siswa123', PASSWORD_BCRYPT),
                'nama_lengkap'  => 'Siswa Peminjam',
                'role_id'       => 3, // Peminjam
                'created_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($data);
    }
}
