<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * SarprasModel
 * 
 * Manages 'sarpras' table (Sarana dan Prasarana).
 * Handles inventory items and their variants.
 */
class SarprasModel extends Model
{
    protected $table            = 'sarpras';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $allowedFields    = [
        'kode', 
        'nama', 
        'kategori_id', 
        'location_id', 
        'stok', 
        'kondisi_id',
        'status',
        'maintenance_interval',
        'last_maintenance_date',
        'next_maintenance_date',
        'tgl_pengadaan',
        'harga_beli',
        'parent_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama'        => 'required|min_length[3]|max_length[150]',
        'kode'        => 'required|max_length[50]',
        'kategori_id' => 'required|numeric',
        'location_id' => 'required|numeric',
        'kondisi_id'  => 'required|numeric',
        'status'      => 'required|in_list[tersedia,dipinjam,rusak,hilang]',
    ];

    protected $validationMessages = [
        'nama' => [
            'required' => 'Nama barang harus diisi.',
            'min_length' => 'Nama barang minimal 3 karakter.'
        ]
    ];

    protected $skipValidation = false;
}
