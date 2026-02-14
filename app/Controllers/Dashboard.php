<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\SarprasModel;
use App\Models\PeminjamanModel;
use App\Models\PengaduanModel;
use App\Models\PengembalianModel;
use App\Models\ActivityLogModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $role = session()->get('role_id');
        
        if ($role == 1) { // Admin

            $sarprasModel = new SarprasModel();
            $peminjamanModel = new PeminjamanModel();
            $pengaduanModel = new PengaduanModel();
            $pengembalianModel = new PengembalianModel();
            
            // Top 5 Defective Items (Based on Maintenance Frequency)
            $db = \Config\Database::connect();
            $topDefects = $db->table('maintenance_records')
                             ->select('sarpras.nama, COUNT(maintenance_records.id) as total_problems')
                             ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id')
                             ->join('sarpras', 'sarpras.id = maintenance_schedules.sarpras_id')
                             ->groupBy('sarpras.nama')
                             ->orderBy('total_problems', 'DESC')
                             ->limit(5)
                             ->get()->getResultArray();

            $activityModel = new ActivityLogModel();
            $recentActivities = $activityModel->select('activity_log.*, users.nama_lengkap')
                                              ->join('users', 'users.id = activity_log.user_id')
                                              ->orderBy('activity_log.created_at', 'DESC')
                                              ->limit(5)
                                              ->findAll();

            $scheduleModel = new \App\Models\MaintenanceScheduleModel();
            $upcomingMaintenance = $scheduleModel->getUpcoming(5);

            // Asset Health Stats
            $totalSarpras = $sarprasModel->where('is_deleted', 0)->countAllResults();
            $goodSarpras = $sarprasModel->where('kondisi_id', 1)->where('is_deleted', 0)->countAllResults();
            $assetHealth = $totalSarpras > 0 ? round(($goodSarpras / $totalSarpras) * 100) : 0;

            $data = [
                'total_sarpras' => $totalSarpras,
                'active_peminjaman' => $peminjamanModel->where('status_id', 2)->countAllResults(), // 2 = Disetujui/Dipinjam
                'damaged_sarpras' => $sarprasModel->whereIn('kondisi_id', [2, 3])->where('is_deleted', 0)->countAllResults(), // Rusak Ringan/Berat & Not Deleted
                'pengaduan_masuk' => $pengaduanModel->where('status_id', 1)->countAllResults(), // 1 = Belum Ditindaklanjuti
                'recent_activities' => $recentActivities,
                'upcoming_maintenance' => $upcomingMaintenance,
                'good_sarpras' => $goodSarpras,
                'asset_health' => $assetHealth,
                'lost_sarpras' => $sarprasModel->where('kondisi_id', 4)->where('is_deleted', 0)->countAllResults(), // Hilang
                'top_defects' => $topDefects
            ];

            return view('admin/dashboard', $data);
            
        } elseif ($role == 2) { // Petugas
            return redirect()->to('/petugas/dashboard');
        } else { // Member
             return redirect()->to('/member/dashboard');
        }
    }
}
