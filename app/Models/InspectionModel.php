<?php

namespace App\Models;

use CodeIgniter\Model;

class InspectionModel extends Model
{
    protected $table            = 'inspeksi_pinjam';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['peminjaman_id', 'inspector_id', 'type', 'inspection_date', 'photo_evidence', 'notes'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = ''; // No update field needed for now
}
