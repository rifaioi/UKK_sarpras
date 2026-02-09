<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceScheduleModel extends Model
{
    protected $table            = 'maintenance_schedules';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'sarpras_id',
        'scheduled_date',
        'maintenance_type',
        'action_type',
        'technician',
        'description',
        'status'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'sarpras_id'     => 'required|numeric',
        'scheduled_date' => 'required|valid_date',
        'maintenance_type' => 'required',
        'action_type'    => 'required',
        'technician'     => 'required',
        'status'         => 'required|in_list[Scheduled,Delayed,Canceled,Completed]',
    ];

    /**
     * Get upcoming schedules
     */
    public function getUpcoming($limit = 5)
    {
        return $this->select('maintenance_schedules.*, sarpras.nama as asset_name, sarpras.kode as asset_kode')
                    ->join('sarpras', 'sarpras.id = maintenance_schedules.sarpras_id')
                    ->where('maintenance_schedules.status', 'Scheduled')
                    ->where('maintenance_schedules.scheduled_date >=', date('Y-m-d'))
                    ->orderBy('maintenance_schedules.scheduled_date', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
}
