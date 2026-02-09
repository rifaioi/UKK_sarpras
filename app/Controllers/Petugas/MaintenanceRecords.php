<?php

namespace App\Controllers\Petugas;

use App\Controllers\Admin\MaintenanceRecords as AdminRecords;

class MaintenanceRecords extends AdminRecords
{
    public function index()
    {
        $q = $this->request->getGet('q');
        
        $query = $this->recordModel->select('maintenance_records.*, ms.maintenance_type, ms.action_type, ms.technician, s.nama as asset_name, s.kode as asset_kode, k.nama_kondisi')
                                   ->join('maintenance_schedules ms', 'ms.id = maintenance_records.schedule_id', 'left')
                                   ->join('sarpras s', 's.id = ms.sarpras_id', 'left')
                                   ->join('kondisi_alat k', 'k.id = maintenance_records.condition_after');

        if ($q) {
            $query->groupStart()
                  ->like('s.nama', $q)
                  ->orLike('ms.technician', $q)
                  ->groupEnd();
        }

        $data = [
            'records' => $query->orderBy('maintenance_records.completion_date', 'DESC')->findAll(),
            'filter_q' => $q
        ];

        return view('petugas/maintenance/records', $data);
    }
}
