<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\SarprasModel;
use App\Models\StatusPeminjamanModel;

class Peminjaman extends BaseController
{
    protected $peminjamanModel;
    protected $sarprasModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->sarprasModel = new SarprasModel();
    }

    /**
     * Display list of loan requests with filtering
     */
    public function index()
    {
        $userId = $this->request->getGet('user_id');
        $sarprasId = $this->request->getGet('sarpras_id');
        $statusId = $this->request->getGet('status_id');

        $query = $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, status_peminjaman.nama_status')
                                       ->join('users', 'users.id = peminjaman.user_id')
                                       ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                       ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id');

        if ($userId) {
            $query->where('peminjaman.user_id', $userId);
        }
        if ($sarprasId) {
            // Find sarpras with same name to filter by 'alat' type
            $targetSarpras = $this->sarprasModel->find($sarprasId);
            if ($targetSarpras) {
                $query->where('sarpras.nama', $targetSarpras['nama']);
            }
        }
        if ($statusId) {
            $query->where('peminjaman.status_id', $statusId);
        }

        $peminjaman = $query->orderBy('peminjaman.created_at', 'DESC')->findAll();

        // Data for filters
        $userModel = new \App\Models\UserModel();
        $data = [
            'peminjaman' => $peminjaman,
            'users' => $userModel->where('role_id', 3)->findAll(), // Members only
            'sarpras_list' => $this->sarprasModel->select('nama, MIN(id) as id')->groupBy('nama')->findAll(),
            'filter_user' => $userId,
            'filter_sarpras' => $sarprasId,
            'filter_status' => $statusId
        ];

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        return view($role . '/peminjaman/index', $data);
    }

    /**
     * Approve a loan request and update stock
     */
    public function approve($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        $item = $this->sarprasModel->find($peminjaman['sarpras_id']);

        if ($item['stok'] < $peminjaman['jumlah']) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi untuk menyetujui peminjaman ini.');
        }

        // Transactions are better for data integrity
        $db = \Config\Database::connect();
        $db->transStart();
        
        // 1. Set specific unit status to 'dipinjam' (T1-PINJAM-010)
        $this->sarprasModel->update($item['id'], [
            'stok' => 0,
            'status' => 'dipinjam'
        ]);

        // 2. Update status to 2 (Disetujui/Dipinjam)
        $this->peminjamanModel->update($id, ['status_id' => 2]);
        
        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses peminjaman.');
        }

        log_activity('Approve Peminjaman', "Menyetujui peminjaman id: $id");

        return redirect()->to('/petugas/peminjaman')->with('success', 'Peminjaman disetujui');
    }

    /**
     * Reject a loan request
     */
    public function reject($id)
    {
        $rejectionReason = $this->request->getPost('rejection_reason');

        $this->peminjamanModel->update($id, [
            'status_id' => 3,
            'rejection_reason' => $rejectionReason
        ]);

        log_activity('Reject Peminjaman', "Menolak peminjaman id: $id" . ($rejectionReason ? " Alasan: $rejectionReason" : ""));
        return redirect()->to('/petugas/peminjaman')->with('success', 'Peminjaman ditolak');
    }
}
