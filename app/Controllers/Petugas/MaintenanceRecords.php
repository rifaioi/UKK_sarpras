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

    /**
     * Redirect to maintenance schedules with pre-selected item
     */
    public function create($sarpras_id = null)
    {
        if ($sarpras_id) {
            $sarpras = $this->sarprasModel->find($sarpras_id);
            if ($sarpras) {
                session()->setFlashdata('preselect_sarpras', $sarpras_id);
                session()->setFlashdata('preselect_sarpras_name', $sarpras['nama'] . ' (' . $sarpras['kode'] . ')');
            }
        }
        
        return redirect()->to('/petugas/maintenance/records')->with('info', 'Silakan buat jadwal perbaikan untuk item terpilih');
    }
}
