<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceScheduleModel extends Model
{
    protected $table = 'maintenance_schedules';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'kategori_id',
        'schedule_name',
        'interval_months',
        'description',
        'is_active',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'kategori_id' => 'required|numeric',
        'schedule_name' => 'required|min_length[3]|max_length[255]',
        'interval_months' => 'required|numeric|greater_than[0]',
    ];

    protected $validationMessages = [
        'interval_months' => [
            'greater_than' => 'Interval harus minimal 1 bulan.',
        ],
    ];

    protected $skipValidation = false;

    /**
     * Get all active maintenance schedules
     */
    public function getActiveSchedules()
    {
        return $this->select('maintenance_schedules.*, kategori_sarpras.nama as nama_kategori')
                    ->join('kategori_sarpras', 'kategori_sarpras.id = maintenance_schedules.kategori_id', 'left')
                    ->where('maintenance_schedules.is_active', 1)
                    ->findAll();
    }

    /**
     * Get schedules for a specific category
     */
    public function getSchedulesByCategory($kategoriId)
    {
        return $this->where('kategori_id', $kategoriId)
                    ->where('is_active', 1)
                    ->findAll();
    }
}
