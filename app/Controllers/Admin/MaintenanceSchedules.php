<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MaintenanceScheduleModel;
use App\Models\KategoriSarprasModel;

class MaintenanceSchedules extends BaseController
{
    protected $scheduleModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->scheduleModel = new MaintenanceScheduleModel();
        $this->kategoriModel = new KategoriSarprasModel();
    }

    public function index()
    {
        $schedules = $this->scheduleModel->getActiveSchedules();

        $data = [
            'title' => 'Jadwal Maintenance',
            'schedules' => $schedules,
        ];

        return view('admin/maintenance/schedules/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Jadwal Maintenance',
            'categories' => $this->kategoriModel->where('is_deleted', 0)->findAll(),
        ];

        return view('admin/maintenance/schedules/form', $data);
    }

    public function store()
    {
        if (!$this->validate($this->scheduleModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->scheduleModel->save([
            'kategori_id' => $this->request->getPost('kategori_id'),
            'schedule_name' => $this->request->getPost('schedule_name'),
            'interval_months' => $this->request->getPost('interval_months'),
            'description' => $this->request->getPost('description'),
            'is_active' => 1,
        ]);

        log_activity('Tambah Jadwal Maintenance', 'Menambahkan jadwal: ' . $this->request->getPost('schedule_name'));

        return redirect()->to('/admin/maintenance/schedules')->with('success', 'Jadwal maintenance berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $schedule = $this->scheduleModel->find($id);

        if (!$schedule) {
            return redirect()->to('/admin/maintenance/schedules')->with('error', 'Jadwal tidak ditemukan.');
        }

        $data = [
            'title' => 'Edit Jadwal Maintenance',
            'schedule' => $schedule,
            'categories' => $this->kategoriModel->where('is_deleted', 0)->findAll(),
        ];

        return view('admin/maintenance/schedules/form', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->scheduleModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->scheduleModel->update($id, [
            'kategori_id' => $this->request->getPost('kategori_id'),
            'schedule_name' => $this->request->getPost('schedule_name'),
            'interval_months' => $this->request->getPost('interval_months'),
            'description' => $this->request->getPost('description'),
        ]);

        log_activity('Update Jadwal Maintenance', 'Update jadwal ID: ' . $id);

        return redirect()->to('/admin/maintenance/schedules')->with('success', 'Jadwal maintenance berhasil diupdate.');
    }

    public function delete($id)
    {
        // Soft delete by setting is_active to 0
        $this->scheduleModel->update($id, ['is_active' => 0]);

        log_activity('Hapus Jadwal Maintenance', 'Nonaktifkan jadwal ID: ' . $id);

        return redirect()->to('/admin/maintenance/schedules')->with('success', 'Jadwal maintenance berhasil dihapus.');
    }
}
