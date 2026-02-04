<?php

namespace App\Models;

use CodeIgniter\Model;

/**
 * UserModel
 * 
 * Manages system users (Admin, Petugas, Member).
 */
class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'username', 
        'password_hash', 
        'nama_lengkap', 
        'role_id',
        'is_deleted'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'username'     => 'required|alpha_numeric|min_length[4]|max_length[100]|is_unique[users.username,id,{id}]',
        'nama_lengkap' => 'required|min_length[3]|max_length[150]',
        'role_id'      => 'required|numeric',
    ];

    protected $validationMessages = [
        'username' => [
            'is_unique' => 'Username sudah digunakan.',
            'alpha_numeric' => 'Username hanya boleh huruf dan angka.'
        ]
    ];

    protected $skipValidation = false;
}
