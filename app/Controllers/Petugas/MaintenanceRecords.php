<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\MaintenanceRecordModel;
use App\Models\MaintenanceScheduleModel;
use App\Models\SarprasModel;
use App\Models\PengembalianModel;

class MaintenanceRecords extends BaseController
{
    protected $recordModel;
    protected $scheduleModel;
    protected $sarprasModel;
    protected $pengembalianModel;

    public function __construct()
    {
        $this->recordModel = new MaintenanceRecordModel();
        $this->scheduleModel = new MaintenanceScheduleModel();
        $this->sarprasModel = new SarprasModel();
        $this->pengembalianModel = new PengembalianModel();
    }

    public function index()
    {
        $records = $this->recordModel->select('maintenance_records.*, 
                                               sarpras.nama as nama_barang, 
                                               sarpras.kode,
                                               maintenance_schedules.schedule_name,
                                               users.nama_lengkap as creator_name')
                                      ->join('sarpras', 'sarpras.id = maintenance_records.sarpras_id')
                                      ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id', 'left')
                                      ->join('users', 'users.id = maintenance_records.created_by', 'left')
                                      ->orderBy('maintenance_records.maintenance_date', 'DESC')
                                      ->findAll();

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'title' => 'Riwayat Maintenance',
            'records' => $records,
        ];

        return view($role . '/maintenance/records/index', $data);
    }

    public function create($sarprasId = null)
    {
        $sarpras = null;

        if ($sarprasId) {
            $sarpras = $this->sarprasModel->find($sarprasId);
        }

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'title' => 'Catat Maintenance',
            'sarpras' => $sarpras,
            'all_sarpras' => $this->sarprasModel->select('id, kode, nama, maintenance_interval')->findAll(),
            'schedules' => $this->scheduleModel->where('is_active', 1)->findAll(),
        ];

        return view($role . '/maintenance/records/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->recordModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $sarprasId = $this->request->getPost('sarpras_id');
        $maintenanceDate = $this->request->getPost('maintenance_date');

        // Get sarpras to check maintenance interval
        $sarpras = $this->sarprasModel->find($sarprasId);
        
        // Calculate next maintenance date from sarpras interval
        $nextMaintenanceDate = null;
        if ($sarpras && $sarpras['maintenance_interval']) {
            $nextMaintenanceDate = date('Y-m-d', strtotime("+{$sarpras['maintenance_interval']} months", strtotime($maintenanceDate)));
        }

        $newKondisiId = $this->request->getPost('new_kondisi_id');

        // Save maintenance record
        $this->recordModel->save([
            'sarpras_id' => $sarprasId,
            'schedule_id' => $this->request->getPost('schedule_id') ?: null,
            'maintenance_date' => $maintenanceDate,
            'performed_by' => $this->request->getPost('performed_by'),
            'description' => $this->request->getPost('description'),
            'cost' => $this->request->getPost('cost') ?: null,
            'new_kondisi_id' => $newKondisiId ?: null,
            'status' => 'Selesai',
            'next_maintenance_date' => $nextMaintenanceDate,
            'notes' => $this->request->getPost('notes'),
            'created_by' => session()->get('id'),
        ]);

        // Update sarpras logic
        $updateData = [
            'last_maintenance_date' => $maintenanceDate,
            'next_maintenance_date' => $nextMaintenanceDate,
        ];

        // If a new condition is set, update it and potentially restock
        if ($newKondisiId) {
            $updateData['kondisi_id'] = $newKondisiId;
            // If condition becomes Baik (1), set status to tersedia and replenish stock for this unit
            if ($newKondisiId == 1) {
                $updateData['status'] = 'tersedia';
                $updateData['stok'] = 1;
            } else {
                $updateData['status'] = 'rusak';
                $updateData['stok'] = 0;
            }
        }

        $this->sarprasModel->update($sarprasId, $updateData);

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        log_activity('Catat Maintenance', 'Maintenance untuk sarpras ID: ' . $sarprasId);

        return redirect()->to('/' . $role . '/maintenance/records')->with('success', 'Maintenance berhasil dicatat.');
    }

    public function history($sarprasId)
    {
        $sarpras = $this->sarprasModel->find($sarprasId);
        if (!$sarpras) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        $records = $this->recordModel->getHistoryByAsset($sarprasId);

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'title' => 'Riwayat Maintenance - ' . $sarpras['nama'],
            'sarpras' => $sarpras,
            'records' => $records,
        ];

        return view($role . '/maintenance/records/history', $data);
    }

    public function timeline($sarprasId)
    {
        $sarpras = $this->sarprasModel->find($sarprasId);
        if (!$sarpras) {
            return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        // Get maintenance records
        $maintenanceRecords = $this->recordModel->getHistoryByAsset($sarprasId);

        // Get breakdown/return records
        $breakdowns = $this->pengembalianModel->select('pengembalian.*, 
                                                        kondisi_alat.nama_kondisi,
                                                        users.nama_lengkap as peminjam')
                                              ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                              ->join('users', 'users.id = peminjaman.user_id')
                                              ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id', 'left')
                                              ->where('peminjaman.sarpras_id', $sarprasId)
                                              ->findAll();

        // Combine and sort by date
        $timeline = [];

        foreach ($maintenanceRecords as $record) {
            $timeline[] = [
                'type' => 'maintenance',
                'date' => $record['maintenance_date'],
                'data' => $record,
            ];
        }

        foreach ($breakdowns as $breakdown) {
            $timeline[] = [
                'type' => 'return',
                'date' => $breakdown['tgl_pengembalian'],
                'data' => $breakdown,
            ];
        }

        // Sort by date descending
        usort($timeline, function($a, $b) {
            return strtotime($b['date']) - strtotime($a['date']);
        });

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'title' => 'Timeline - ' . $sarpras['nama'],
            'sarpras' => $sarpras,
            'timeline' => $timeline,
        ];

        return view($role . '/maintenance/records/timeline', $data);
    }

    public function upcomingDue()
    {
        // Get upcoming maintenance from sarpras table directly
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime('+30 days')); // Show for a month

        $upcoming = $this->sarprasModel->select('sarpras.*, kategori_sarpras.nama as kategori_nama,
                                                  DATEDIFF(sarpras.next_maintenance_date, CURDATE()) as days_until_due')
                                        ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
                                        ->where('sarpras.next_maintenance_date >=', $today)
                                        ->where('sarpras.next_maintenance_date <=', $futureDate)
                                        ->orderBy('sarpras.next_maintenance_date', 'ASC')
                                        ->findAll();

        $overdue = $this->sarprasModel->select('sarpras.*, kategori_sarpras.nama as kategori_nama,
                                                 DATEDIFF(CURDATE(), sarpras.next_maintenance_date) as days_overdue')
                                      ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
                                      ->where('sarpras.next_maintenance_date <', $today)
                                      ->where('sarpras.next_maintenance_date IS NOT NULL')
                                      ->orderBy('sarpras.next_maintenance_date', 'ASC')
                                      ->findAll();

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'title' => 'Unit Perlu Maintenance',
            'upcoming' => $upcoming,
            'overdue' => $overdue,
        ];

        return view($role . '/maintenance/records/upcoming', $data);
    }

    /**
     * Report for Most Frequently Damaged Units (Tier 2.2)
     */
    public function frequentDamage()
    {
        $breakdowns = $this->pengembalianModel->select('
            peminjaman.sarpras_id,
            sarpras.nama as nama_barang,
            sarpras.kode,
            kategori_sarpras.nama as kategori_nama,
            COUNT(*) as total_kembali,
            SUM(CASE WHEN pengembalian.kondisi_id != 1 THEN 1 ELSE 0 END) as damage_count
        ')
        ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
        ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
        ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
        ->groupBy('peminjaman.sarpras_id')
        ->having('damage_count > 0')
        ->orderBy('damage_count', 'DESC')
        ->limit(20)
        ->findAll();

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'title' => 'Analisis Unit Sering Rusak',
            'breakdowns' => $breakdowns,
        ];

        return view($role . '/maintenance/frequent_damage', $data);
    }
}
