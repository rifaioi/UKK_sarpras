<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ActivityLogModel;

class ActivityLog extends BaseController
{
    protected $logModel;

    public function __construct()
    {
        $this->logModel = new ActivityLogModel();
    }

    public function index()
    {
        $data = [
            'logs' => $this->logModel->select('activity_log.*, users.nama_lengkap, roles.nama_role')
                                     ->join('users', 'users.id = activity_log.user_id')
                                     ->join('roles', 'roles.id = users.role_id')
                                     ->orderBy('activity_log.created_at', 'DESC')
                                     ->findAll()
        ];
        return view('admin/activity_log/index', $data);
    }
}
