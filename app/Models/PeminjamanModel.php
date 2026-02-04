<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * PeminjamanModel
 * 
 * Manages school asset loan transactions.
 */
class PeminjamanModel extends Model
{
    protected $table            = 'peminjaman';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'kode_peminjaman',
        'user_id', 
        'sarpras_id', 
        'jumlah', 
        'tgl_pinjam', 
        'tgl_kembali_rencana',
        'tujuan',
        'status_id',
        'rejection_reason',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'user_id'             => 'required|numeric',
        'sarpras_id'          => 'required|numeric',
        'jumlah'              => 'required|numeric|greater_than[0]',
        'tgl_pinjam'          => 'required|valid_date',
        'tgl_kembali_rencana' => 'required|valid_date',
        'status_id'           => 'required|numeric',
    ];

    protected $validationMessages = [
        'jumlah' => [
            'greater_than' => 'Jumlah pinjam minimal 1.'
        ]
    ];

    protected $skipValidation = false;
}
