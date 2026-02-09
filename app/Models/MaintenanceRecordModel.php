<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceRecordModel extends Model
{
    protected $table            = 'maintenance_records';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'schedule_id',
        'completion_date',
        'result',
        'condition_after',
        'cost',
        'notes'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Validation
    protected $validationRules = [
        'completion_date' => 'required|valid_date',
        'result'          => 'required',
        'condition_after' => 'required|numeric',
    ];
}
