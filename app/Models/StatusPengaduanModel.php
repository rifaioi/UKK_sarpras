<?php

namespace App\Models;

use CodeIgniter\Model;

class StatusPengaduanModel extends Model
{
    protected $table            = 'status_pengaduan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_status'];
}
