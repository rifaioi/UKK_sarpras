<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\SarprasModel;
use App\Models\PeminjamanModel;
use App\Models\PengaduanModel;
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
            
            // Chart Data: Borrows per month for current year
            $currentYear = date('Y');
            $monthlyStats = [];
            for ($m=1; $m<=12; $m++) {
                $monthlyStats[] = $peminjamanModel->where('YEAR(created_at)', $currentYear)
                                                  ->where('MONTH(created_at)', $m)
                                                  ->countAllResults();
            }

            $activityModel = new ActivityLogModel();
            $recentActivities = $activityModel->select('activity_log.*, users.nama_lengkap')
                                              ->join('users', 'users.id = activity_log.user_id')
                                              ->orderBy('activity_log.created_at', 'DESC')
                                              ->limit(5)
                                              ->findAll();

            $data = [

                'total_sarpras' => $sarprasModel->where('is_deleted', 0)->countAllResults(),
                'active_peminjaman' => $peminjamanModel->where('status_id', 2)->countAllResults(), // 2 = Disetujui/Dipinjam
                'damaged_sarpras' => $sarprasModel->where('is_deleted', 0)->where('kondisi_id !=', 1)->countAllResults(), // Assuming 1 = Baik
                'pengaduan_masuk' => $pengaduanModel->where('status_id', 1)->countAllResults(), // 1 = Belum Ditindaklanjuti
                'chart_data' => json_encode($monthlyStats),
                'recent_activities' => $recentActivities
            ];

            return view('admin/dashboard', $data);
            
        } elseif ($role == 2) { // Petugas
            return redirect()->to('/petugas/dashboard');
        } else { // Member
             return redirect()->to('/member/dashboard');
        }
    }
}
