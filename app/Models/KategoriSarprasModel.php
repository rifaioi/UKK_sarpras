<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriSarprasModel extends Model
{
    protected $table            = 'kategori_sarpras';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama', 'is_deleted'];
}
