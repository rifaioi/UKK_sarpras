<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MaintenanceRecordModel;
use App\Models\MaintenanceScheduleModel;
use App\Models\SarprasModel;
use App\Models\KondisiAlatModel;

class MaintenanceRecords extends BaseController
{
    protected $recordModel;
    protected $scheduleModel;
    protected $sarprasModel;
    protected $kondisiModel;

    public function __construct()
    {
        $this->recordModel = new MaintenanceRecordModel();
        $this->scheduleModel = new MaintenanceScheduleModel();
        $this->sarprasModel = new SarprasModel();
        $this->kondisiModel = new KondisiAlatModel();
    }

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

        $records = $query->orderBy('maintenance_records.completion_date', 'DESC')->findAll();

        $data = [
            'records' => $records,
            'filter_q' => $q
        ];

        return view('admin/maintenance/records', $data);
    }

    /**
     * Redirect to maintenance schedules with pre-selected item
     */
    public function create($sarpras_id = null)
    {
        if ($sarpras_id) {
            $sarpras = $this->sarprasModel->find($sarpras_id);
            if ($sarpras) {
                // Store selection in session to pre-fill form
                session()->setFlashdata('preselect_sarpras', $sarpras_id);
                session()->setFlashdata('preselect_sarpras_name', $sarpras['nama'] . ' (' . $sarpras['kode'] . ')');
            }
        }
        
        return redirect()->to('/admin/maintenance/schedules')->with('info', 'Silakan buat jadwal perbaikan untuk item terpilih');
    }

    /**
     * Store maintenance result and update asset
     */
    public function store()
    {
        $rules = [
            'schedule_id'     => 'required|numeric',
            'completion_date' => 'required|valid_date',
            'result'          => 'required',
            'condition_after' => 'required|numeric',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $scheduleId = $this->request->getPost('schedule_id');
        $schedule = $this->scheduleModel->find($scheduleId);

        if (!$schedule) {
            return redirect()->back()->with('error', 'Jadwal tidak ditemukan');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Save results
        $this->recordModel->save([
            'schedule_id'     => $scheduleId,
            'completion_date' => $this->request->getPost('completion_date'),
            'result'          => $this->request->getPost('result'),
            'condition_after' => $this->request->getPost('condition_after'),
            'cost'            => $this->request->getPost('cost') ?: 0,
            'notes'           => $this->request->getPost('notes'),
        ]);

        // 2. Update schedule status
        $this->scheduleModel->update($scheduleId, ['status' => 'Completed']);

        // 3. Update Asset
        $sarpras = $this->sarprasModel->find($schedule['sarpras_id']);
        $nextDate = null;
        if ($sarpras['maintenance_interval'] > 0) {
            $nextDate = date('Y-m-d', strtotime($this->request->getPost('completion_date') . ' + ' . $sarpras['maintenance_interval'] . ' days'));
        }

        $this->sarprasModel->update($schedule['sarpras_id'], [
            'kondisi_id'            => $this->request->getPost('condition_after'),
            'last_maintenance_date' => $this->request->getPost('completion_date'),
            'next_maintenance_date' => $nextDate,
            'status'                => 'tersedia' // Reset to available after maintenance
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
             return redirect()->back()->with('error', 'Gagal menyimpan data maintenance');
        }

        log_activity('Maintenance', 'Selesaikan Maintenance', "Menyelesaikan maintenance asset id: " . $schedule['sarpras_id']);
        return redirect()->back()->with('success', 'Maintenance berhasil dicatat');
    }
}
