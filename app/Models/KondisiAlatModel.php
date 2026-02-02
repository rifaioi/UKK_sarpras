<?php

namespace App\Models;

use CodeIgniter\Model;

class KondisiAlatModel extends Model
{
    protected $table            = 'kondisi_alat';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_kondisi'];
}
