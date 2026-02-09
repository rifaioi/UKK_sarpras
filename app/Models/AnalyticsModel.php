<?php

namespace App\Models;

use CodeIgniter\Model;

class AnalyticsModel extends Model
{
    /**
     * Top 10 most damaged assets
     */
    public function getTopDamagedAssets()
    {
        return $this->db->table('pengembalian')
            ->select('sarpras.nama, sarpras.kode, COUNT(pengembalian.id) as damage_count')
            ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
            ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
            ->whereIn('pengembalian.kondisi_id', [2, 3]) // Rusak Ringan/Berat
            ->groupBy('sarpras.id')
            ->orderBy('damage_count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    /**
     * Damage distribution by category
     */
    public function getDamageByCategory()
    {
        return $this->db->table('pengembalian')
            ->select('kategori_sarpras.nama as category_name, COUNT(pengembalian.id) as damage_count')
            ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
            ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
            ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
            ->whereIn('pengembalian.kondisi_id', [2, 3])
            ->groupBy('kategori_sarpras.id')
            ->get()
            ->getResultArray();
    }

    /**
     * Damage trend over the last 6 months
     */
    public function getDamageTrend()
    {
        return $this->db->table('pengembalian')
            ->select("DATE_FORMAT(tgl_pengembalian, '%Y-%m') as month, COUNT(id) as damage_count")
            ->whereIn('kondisi_id', [2, 3])
            ->where('tgl_pengembalian >=', date('Y-m-d', strtotime('-6 months')))
            ->groupBy('month')
            ->orderBy('month', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Damage count by user
     */
    public function getDamageByUser()
    {
        return $this->db->table('pengembalian')
            ->select('users.nama_lengkap as user_name, COUNT(pengembalian.id) as damage_count')
            ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->whereIn('pengembalian.kondisi_id', [2, 3])
            ->groupBy('users.id')
            ->orderBy('damage_count', 'DESC')
            ->limit(10)
            ->get()
            ->getResultArray();
    }

    /**
     * Asset Lifecycle Data
     */
    public function getAssetLifecycleData()
    {
        return $this->db->table('sarpras')
            ->select('sarpras.*, kategori_sarpras.nama as category_name, kategori_sarpras.expected_lifespan')
            ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
            ->where('sarpras.is_deleted', 0)
            ->get()
            ->getResultArray();
    }

    /**
     * Get total maintenance cost per asset
     */
    public function getMaintenanceCosts()
    {
        return $this->db->table('maintenance_records')
            ->select('maintenance_schedules.sarpras_id, SUM(maintenance_records.cost) as total_cost')
            ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id')
            ->groupBy('maintenance_schedules.sarpras_id')
            ->get()
            ->getResultArray();
    }
}
