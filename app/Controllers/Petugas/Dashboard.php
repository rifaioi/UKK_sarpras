<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\PengaduanModel;
use App\Models\ActivityLogModel;

class Dashboard extends BaseController
{
    protected $peminjamanModel;
    protected $pengaduanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->pengaduanModel = new PengaduanModel();
    }

    public function index()
    {
        // Status 1: Menunggu Persetujuan
        $pendingPeminjaman = $this->peminjamanModel->where('status_id', 1)->countAllResults();
        
        // Status 2: Disetujui (Sedang dipinjam)
        $activePeminjaman = $this->peminjamanModel->where('status_id', 2)->countAllResults();

        // Status 1 or 2: Pending/Process Pengaduan
        // Assuming 1=Belum, 2=Proses
        $activePengaduan = $this->pengaduanModel->whereIn('status_id', [1, 2])->countAllResults();

        $activityModel = new ActivityLogModel();
        $recentActivities = $activityModel->select('activity_log.*, users.nama_lengkap')
                                          ->join('users', 'users.id = activity_log.user_id')
                                          ->orderBy('activity_log.created_at', 'DESC')
                                          ->limit(5)
                                          ->findAll();

        $data = [
            'pending_peminjaman' => $pendingPeminjaman,
            'active_peminjaman' => $activePeminjaman,
            'active_pengaduan' => $activePengaduan,
            'recent_activities' => $recentActivities
        ];

        return view('petugas/dashboard', $data);
    }
}
