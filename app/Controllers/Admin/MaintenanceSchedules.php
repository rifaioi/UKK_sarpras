<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MaintenanceScheduleModel;
use App\Models\SarprasModel;

class MaintenanceSchedules extends BaseController
{
    protected $scheduleModel;
    protected $sarprasModel;
    protected $categoryModel;

    public function __construct()
    {
        $this->scheduleModel = new MaintenanceScheduleModel();
        $this->sarprasModel = new SarprasModel();
        $this->categoryModel = new \App\Models\KategoriSarprasModel();
    }

    public function index()
    {
        $q = $this->request->getGet('q');
        $status = $this->request->getGet('status');

        $query = $this->scheduleModel->select('maintenance_schedules.*, sarpras.nama as asset_name, sarpras.kode as asset_kode')
                                     ->join('sarpras', 'sarpras.id = maintenance_schedules.sarpras_id');

        if ($q) {
            $query->groupStart()
                  ->like('sarpras.nama', $q)
                  ->orLike('maintenance_schedules.technician', $q)
                  ->groupEnd();
        }

        if ($status) {
            $query->where('maintenance_schedules.status', $status);
        }

        $schedules = $query->orderBy('maintenance_schedules.scheduled_date', 'DESC')->findAll();

        $data = [
            'schedules' => $schedules,
            'assets' => $this->sarprasModel->where('is_deleted', 0)->findAll(),
            'categories' => $this->categoryModel->where('is_deleted', 0)->findAll(),
            'filter_q' => $q,
            'filter_status' => $status
        ];

        return view('admin/maintenance/index', $data);
    }

    public function store()
    {
        $rules = [
            'scheduled_date'  => 'required|valid_date',
            'maintenance_type' => 'required',
            'action_type'     => 'required',
            'technician'      => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $sarprasIds = $this->request->getPost('sarpras_ids');
        $kategoriIds = $this->request->getPost('kategori_ids');
        
        $finalSarprasIds = [];

        if ($this->request->getPost('selection_mode') == 'kategori' && !empty($kategoriIds)) {
            $assets = $this->sarprasModel->whereIn('kategori_id', (array)$kategoriIds)
                                         ->where('is_deleted', 0)
                                         ->findAll();
            foreach ($assets as $asset) {
                $finalSarprasIds[] = $asset['id'];
            }
        } else if (!empty($sarprasIds)) {
            $finalSarprasIds = (array)$sarprasIds;
        }

        if (empty($finalSarprasIds)) {
            return redirect()->back()->with('error', 'Silakan pilih setidaknya satu aset atau kategori');
        }

        foreach ($finalSarprasIds as $id) {
            $this->scheduleModel->save([
                'sarpras_id'       => $id,
                'scheduled_date'   => $this->request->getPost('scheduled_date'),
                'maintenance_type' => $this->request->getPost('maintenance_type'),
                'action_type'      => $this->request->getPost('action_type'),
                'technician'       => $this->request->getPost('technician'),
                'description'      => $this->request->getPost('description'),
                'status'           => 'Scheduled'
            ]);
        }

        log_activity('Maintenance', 'Tambah Jadwal Maintenance', "Menambahkan jadwal maintenance baru");
        return redirect()->to('/admin/maintenance/schedules')->with('success', 'Jadwal maintenance berhasil disimpan');
    }

    public function delete($id)
    {
        $this->scheduleModel->update($id, ['status' => 'Canceled']);
        log_activity('Maintenance', 'Batalkan Jadwal Maintenance', "Membatalkan jadwal maintenance id: $id");
        return redirect()->to('/admin/maintenance/schedules')->with('success', 'Jadwal maintenance dibatalkan');
    }
}
