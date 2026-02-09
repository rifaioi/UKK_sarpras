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
        $pendingPeminjaman = $this->peminjamanModel->where('status_id', 1)->countAllResults();
        
        $activePeminjaman = $this->peminjamanModel->where('status_id', 2)->countAllResults();

        $activePengaduan = $this->pengaduanModel->whereIn('status_id', [1, 2])->countAllResults();

        $activityModel = new ActivityLogModel();
        $recentActivities = $activityModel->select('activity_log.*, users.nama_lengkap')
                                          ->join('users', 'users.id = activity_log.user_id')
                                          ->orderBy('activity_log.created_at', 'DESC')
                                          ->limit(5)
                                          ->findAll();

        // Maintenance Reminders - read from sarpras.next_maintenance_date directly
        $sarprasModel = new \App\Models\SarprasModel();
        $today = date('Y-m-d');
        $reminderDate = date('Y-m-d', strtotime('+7 days'));
        
        $maintenanceReminders = $sarprasModel->select('sarpras.id, sarpras.nama, sarpras.kode, sarpras.next_maintenance_date,
                                                        DATEDIFF(sarpras.next_maintenance_date, CURDATE()) as days_until')
                                              ->where('sarpras.next_maintenance_date IS NOT NULL')
                                              ->where('sarpras.next_maintenance_date <=', $reminderDate)
                                              ->orderBy('sarpras.next_maintenance_date', 'ASC')
                                              ->findAll();

        $data = [
            'pending_peminjaman' => $pendingPeminjaman,
            'active_peminjaman' => $activePeminjaman,
            'active_pengaduan' => $activePengaduan,
            'recent_activities' => $recentActivities,
            'maintenance_reminders' => $maintenanceReminders,
        ];

        return view('petugas/dashboard', $data);
    }
}
