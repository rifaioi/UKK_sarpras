<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class StatusSeeder extends Seeder
{
    public function run()
    {
        // Disable foreign key checks to allow truncate
        $this->db->disableForeignKeyChecks();

        // Status Peminjaman
        $this->db->table('status_peminjaman')->truncate();
        $statusPeminjaman = [
            ['nama_status' => 'Menunggu Persetujuan'],
            ['nama_status' => 'Disetujui'],
            ['nama_status' => 'Ditolak'],
            ['nama_status' => 'Dikembalikan'],
        ];
        $this->db->table('status_peminjaman')->insertBatch($statusPeminjaman);

        // Status Pengaduan
        $this->db->table('status_pengaduan')->truncate();
        $statusPengaduan = [
            ['nama_status' => 'Belum Ditindaklanjuti'],
            ['nama_status' => 'Sedang Diproses'],
            ['nama_status' => 'Selesai'],
            ['nama_status' => 'Ditutup'],
        ];
        $this->db->table('status_pengaduan')->insertBatch($statusPengaduan);

        // Kondisi Alat
        $this->db->table('kondisi_alat')->truncate();
        $kondisiAlat = [
            ['nama_kondisi' => 'Baik'],
            ['nama_kondisi' => 'Rusak Ringan'],
            ['nama_kondisi' => 'Rusak Berat'],
            ['nama_kondisi' => 'Hilang'],
        ];
        $this->db->table('kondisi_alat')->insertBatch($kondisiAlat);
        
        // Tipe Kategori
        $this->db->table('tipe_kategori')->truncate();
        $tipe = [
             ['nama_tipe' => 'Barang'],
             ['nama_tipe' => 'Ruangan'],
        ];
        $this->db->table('tipe_kategori')->insertBatch($tipe);

        $this->db->enableForeignKeyChecks();
    }
}
