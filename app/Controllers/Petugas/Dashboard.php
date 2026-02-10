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

        $scheduleModel = new \App\Models\MaintenanceScheduleModel();
        $upcomingMaintenance = $scheduleModel->getUpcoming(5);

        // Top 5 Defective Items
        $db = \Config\Database::connect();
        $topDefects = $db->table('maintenance_records')
                         ->select('sarpras.nama, COUNT(maintenance_records.id) as total_problems')
                         ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id')
                         ->join('sarpras', 'sarpras.id = maintenance_schedules.sarpras_id')
                         ->groupBy('sarpras.nama')
                         ->orderBy('total_problems', 'DESC')
                         ->limit(5)
                         ->get()->getResultArray();

        $data = [
            'pending_peminjaman' => $pendingPeminjaman,
            'active_peminjaman' => $activePeminjaman,
            'active_pengaduan' => $activePengaduan,
            'recent_activities' => $recentActivities,
            'upcoming_maintenance' => $upcomingMaintenance,
            'top_defects' => $topDefects
        ];

        return view('petugas/dashboard', $data);
    }
}
