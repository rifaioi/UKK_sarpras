<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceRecordModel extends Model
{
    protected $table = 'maintenance_records';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'sarpras_id',
        'schedule_id',
        'maintenance_date',
        'performed_by',
        'description',
        'cost',
        'new_kondisi_id',
        'status',
        'created_by',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    protected $validationRules = [
        'sarpras_id' => 'required|numeric',
        'maintenance_date' => 'required|valid_date',
        'performed_by' => 'required|min_length[3]',
        'description' => 'required|min_length[5]',
    ];

    protected $skipValidation = false;

    /**
     * Get maintenance history for a specific asset
     */
    public function getHistoryByAsset($sarprasId)
    {
        return $this->select('maintenance_records.*, 
                             users.nama_lengkap as creator_name,
                             maintenance_schedules.schedule_name')
                    ->join('users', 'users.id = maintenance_records.created_by', 'left')
                    ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id', 'left')
                    ->where('maintenance_records.sarpras_id', $sarprasId)
                    ->orderBy('maintenance_records.maintenance_date', 'DESC')
                    ->findAll();
    }

    /**
     * Get assets with upcoming maintenance due (within N days)
     */
    public function getUpcomingDue($days = 7)
    {
        $today = date('Y-m-d');
        $futureDate = date('Y-m-d', strtotime("+$days days"));

        return $this->select('maintenance_records.*, 
                             sarpras.nama as nama_barang,
                             sarpras.kode,
                             maintenance_schedules.schedule_name,
                             DATEDIFF(maintenance_records.next_maintenance_date, CURDATE()) as days_until_due')
                    ->join('sarpras', 'sarpras.id = maintenance_records.sarpras_id')
                    ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id', 'left')
                    ->where('maintenance_records.next_maintenance_date >=', $today)
                    ->where('maintenance_records.next_maintenance_date <=', $futureDate)
                    ->orderBy('maintenance_records.next_maintenance_date', 'ASC')
                    ->findAll();
    }

    /**
     * Get overdue maintenance (past due date)
     */
    public function getOverdue()
    {
        $today = date('Y-m-d');

        return $this->select('maintenance_records.*, 
                             sarpras.nama as nama_barang,
                             sarpras.kode,
                             maintenance_schedules.schedule_name,
                             DATEDIFF(CURDATE(), maintenance_records.next_maintenance_date) as days_overdue')
                    ->join('sarpras', 'sarpras.id = maintenance_records.sarpras_id')
                    ->join('maintenance_schedules', 'maintenance_schedules.id = maintenance_records.schedule_id', 'left')
                    ->where('maintenance_records.next_maintenance_date <', $today)
                    ->orderBy('maintenance_records.next_maintenance_date', 'ASC')
                    ->findAll();
    }

    /**
     * Calculate next maintenance date based on schedule
     */
    public function calculateNextMaintenanceDate($maintenanceDate, $intervalMonths)
    {
        return date('Y-m-d', strtotime($maintenanceDate . " +$intervalMonths months"));
    }

    /**
     * Get latest maintenance record for an asset
     */
    public function getLatestByAsset($sarprasId)
    {
        return $this->where('sarpras_id', $sarprasId)
                    ->orderBy('maintenance_date', 'DESC')
                    ->first();
    }
}
