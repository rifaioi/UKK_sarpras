<?php

namespace App\Models;

use CodeIgniter\Model;

class InspectionChecklistItemModel extends Model
{
    protected $table            = 'inspection_template_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['kategori_id', 'nama_item', 'is_deleted'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'kategori_id' => 'required|integer',
        'nama_item'  => 'required|min_length[3]|max_length[255]',
    ];
}
