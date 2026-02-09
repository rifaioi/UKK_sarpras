<?php

namespace App\Models;

use CodeIgniter\Model;

class InspectionResultModel extends Model
{
    protected $table            = 'inspeksi_checklist';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['inspection_id', 'checklist_item_id', 'status', 'description'];
}
