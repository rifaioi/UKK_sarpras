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
        $userId = $this->request->getGet('user_id');
        $action = $this->request->getGet('action');

        $query = $this->logModel->select('activity_log.*, users.nama_lengkap, roles.nama_role')
                                 ->join('users', 'users.id = activity_log.user_id', 'left')
                                 ->join('roles', 'roles.id = users.role_id', 'left');

        if ($userId) {
            $query->where('activity_log.user_id', $userId);
        }
        if ($action) {
            $query->like('activity_log.aksi', $action);
        }

        // $logs = $query->orderBy('activity_log.created_at', 'DESC')->findAll();
        $logs = $query->orderBy('activity_log.created_at', 'DESC')->paginate(10);
        $pager = $this->logModel->pager;

        $userModel = new \App\Models\UserModel();
        $data = [
            'logs' => $logs,
            'pager' => $pager,
            'users' => $userModel->findAll(),
            'filter_user' => $userId,
            'filter_action' => $action,
            'currentPage' => $this->request->getVar('page') ? $this->request->getVar('page') : 1,
            'perPage' => 10
        ];
        return view('admin/activity_log/index', $data);
    }

    public function exportCSV()
    {
        $logs = $this->logModel->select('activity_log.*, users.nama_lengkap, roles.nama_role')
                                 ->join('users', 'users.id = activity_log.user_id', 'left')
                                 ->join('roles', 'roles.id = users.role_id', 'left')
                                 ->orderBy('activity_log.created_at', 'DESC')
                                 ->findAll();

        $filename = 'activity_log_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Waktu', 'User', 'Role', 'Modul', 'Aksi', 'Deskripsi', 'User Agent', 'IP Address', 'Metadata']);

        foreach ($logs as $log) {
            fputcsv($output, [
                $log['id'],
                $log['created_at'],
                $log['nama_lengkap'] ?? 'System',
                $log['nama_role'] ?? 'N/A',
                $log['module'],
                $log['aksi'],
                $log['deskripsi'],
                $log['user_agent'],
                $log['ip_address'],
                $log['metadata']
            ]);
        }

        fclose($output);
        exit;
    }
}
