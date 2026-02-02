<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\PengaduanModel;
use App\Models\SarprasModel;

/**
 * Laporan Controller
 * 
 * Handles generation of various system reports for Admin.
 */
class Laporan extends BaseController
{
    protected $peminjamanModel;
    protected $pengaduanModel;
    protected $sarprasModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->pengaduanModel = new PengaduanModel();
        $this->sarprasModel = new SarprasModel();
    }

    /**
     * Peminjaman Report with date filter
     */
    public function peminjaman()
    {
        $tgl_awal = $this->request->getGet('tgl_awal');
        $tgl_akhir = $this->request->getGet('tgl_akhir');

        $query = $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, status_peminjaman.nama_status')
                                        ->join('users', 'users.id = peminjaman.user_id')
                                        ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                        ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id');

        if ($tgl_awal && $tgl_akhir) {
            $query->where('peminjaman.tgl_pinjam >=', $tgl_awal)
                  ->where('peminjaman.tgl_pinjam <=', $tgl_akhir);
        }

        $data = [
            'peminjaman' => $query->orderBy('peminjaman.created_at', 'DESC')->findAll(),
            'filter' => [
                'tgl_awal' => $tgl_awal,
                'tgl_akhir' => $tgl_akhir
            ]
        ];
        return view('admin/laporan/peminjaman', $data);
    }
    
    /**
     * Pengaduan Report with status filter
     */
    public function pengaduan()
    {
        $status_id = $this->request->getGet('status_id');

        $query = $this->pengaduanModel->select('pengaduan.*, users.nama_lengkap, status_pengaduan.nama_status')
                                      ->join('users', 'users.id = pengaduan.user_id')
                                      ->join('status_pengaduan', 'status_pengaduan.id = pengaduan.status_id')
                                      ->where('pengaduan.is_deleted', 0);

        if ($status_id) {
            $query->where('pengaduan.status_id', $status_id);
        }

        $db = \Config\Database::connect();
        $statuses = $db->table('status_pengaduan')->get()->getResultArray();

        $data = [
            'pengaduan' => $query->orderBy('pengaduan.created_at', 'DESC')->findAll(),
            'statuses' => $statuses,
            'filter_status' => $status_id
        ];
        return view('admin/laporan/pengaduan', $data);
    }

    /**
     * Asset Health Report
     * Visualizes the current condition of assets based on return history.
     */
    public function asset_health()
    {
        $db = \Config\Database::connect();
        
        // Count returns by condition
        $conditions = $db->table('pengembalian')
                         ->select('kondisi_alat.nama_kondisi, COUNT(*) as jumlah')
                         ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                         ->groupBy('pengembalian.kondisi_id')
                         ->get()->getResultArray();

        // Get list of currently damaged items (Condition != Baik)
        // This is based on transactions (Pengembalian)
        $damaged_items = $db->table('pengembalian')
                            ->select('sarpras.nama as nama_barang, users.nama_lengkap as peminjam, kondisi_alat.nama_kondisi, pengembalian.tgl_pengembalian, pengembalian.deskripsi')
                            ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                            ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                            ->join('users', 'users.id = peminjaman.user_id')
                            ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                            ->where('pengembalian.kondisi_id !=', 1) // Assuming 1 is Baik
                            ->orderBy('pengembalian.tgl_pengembalian', 'DESC')
                            ->get()->getResultArray();

        $data = [
            'conditions' => $conditions,
            'damaged_items' => $damaged_items
        ];
        
        return view('admin/laporan/asset_health', $data);
    }
}
