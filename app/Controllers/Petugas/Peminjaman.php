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

    public function index()
    {
        $userId = $this->request->getGet('user_id');
        $sarprasId = $this->request->getGet('sarpras_id');
        $statusId = $this->request->getGet('status_id');
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        $q = $this->request->getGet('q');

        $query = $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, status_peminjaman.nama_status')
                                       ->join('users', 'users.id = peminjaman.user_id')
                                       ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                       ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id');

        if ($userId) {
            $query->where('peminjaman.user_id', $userId);
        }
        if ($sarprasId) {
            $targetSarpras = $this->sarprasModel->find($sarprasId);
            if ($targetSarpras) {
                $query->where('sarpras.nama', $targetSarpras['nama']);
            }
        }
        if ($statusId) {
            $query->where('peminjaman.status_id', $statusId);
        }
        if ($dateFrom) {
            $query->where('peminjaman.tgl_pinjam >=', $dateFrom);
        }
        if ($dateTo) {
            $query->where('peminjaman.tgl_pinjam <=', $dateTo);
        }
        if ($q) {
            $query->groupStart()
                  ->like('peminjaman.kode_peminjaman', $q)
                  ->orLike('users.nama_lengkap', $q)
                  ->orLike('sarpras.nama', $q)
                  ->groupEnd();
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
            'filter_status' => $statusId,
            'filter_date_from' => $dateFrom,
            'filter_date_to' => $dateTo,
            'filter_q' => $q
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
            // T1-PINJAM-FIX: Try to find another unit of the same type that is available
            $alternative = $this->sarprasModel->where('nama', $item['nama'])
                                              ->where('kategori_id', $item['kategori_id'])
                                              ->where('location_id', $item['location_id'])
                                              ->where('kondisi_id', 1)
                                              ->where('status', 'tersedia')
                                              ->where('stok >', 0)
                                              ->first();
            
            if ($alternative) {
                // Swap the unit
                $item = $alternative;
                $this->peminjamanModel->update($id, ['sarpras_id' => $item['id']]);
            } else {
                return redirect()->back()->with('error', 'Stok tidak mencukupi untuk menyetujui peminjaman ini.');
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();
        
        // T1-PINJAM-010
        $this->sarprasModel->skipValidation(true)->update($item['id'], [
            'stok' => 0,
            'status' => 'dipinjam' // Item reserved
        ]);

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal memproses peminjaman.');
        }

        log_activity('Setujui Peminjaman', "Menyetujui peminjaman id: $id");

        // Redirect to Inspeksi Keluar
        return redirect()->to('/petugas/inspections/create/' . $id . '?type=keluar')->with('success', 'Silakan lakukan inspeksi kondisi barang sebelum diserahkan.');
    }

    /**
     * Reject a loan request
     */
    public function reject($id)
    {
        $rejectionReason = $this->request->getPost('rejection_reason');

        $peminjaman = $this->peminjamanModel->find($id);
        if ($peminjaman && $peminjaman['status_id'] == 2) {
            // Restore unit if it was already reserved/approved
            $this->sarprasModel->skipValidation(true)->update($peminjaman['sarpras_id'], [
                'stok' => 1,
                'status' => 'tersedia'
            ]);
        }

        $this->peminjamanModel->update($id, [
            'status_id' => 3,
            'rejection_reason' => $rejectionReason
        ]);

        log_activity('Reject Peminjaman', "Menolak peminjaman id: $id" . ($rejectionReason ? " Alasan: $rejectionReason" : ""));
        return redirect()->to('/petugas/peminjaman')->with('success', 'Peminjaman ditolak dan stok dikembalikan');
    }

    public function delete($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        if (!$peminjaman) {
             $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
             return redirect()->to('/' . $role . '/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        // Forbid deleting active loans to maintain data integrity
        if ($peminjaman['status_id'] == 2) { // Disetujui
             $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
             return redirect()->to('/' . $role . '/peminjaman')->with('error', 'Peminjaman masih berjalan (Disetujui). Silahkan proses pengembalian terlebih dahulu.');
        }

        $this->peminjamanModel->delete($id);
        $roleName = session()->get('role_id') == 1 ? 'Admin' : 'Petugas';
        log_activity('Hapus Peminjaman', "Menghapus data peminjaman id: $id ($roleName)");
        $roleUrl = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        return redirect()->to('/' . $roleUrl . '/peminjaman')->with('success', 'Data peminjaman dihapus');
    }

    /**
     * Print loan receipt with QR code
     */
    public function print($id)
    {
        $peminjaman = $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, sarpras.kode, status_peminjaman.nama_status')
                                            ->join('users', 'users.id = peminjaman.user_id')
                                            ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                            ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id')
                                            ->find($id);
        
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';

        if (!$peminjaman) {
            return redirect()->to('/' . $role . '/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        $loanCode = $peminjaman['kode_peminjaman'] ?? ($peminjaman['kode'] . '-' . str_pad($id, 5, '0', STR_PAD_LEFT));

        $data = [
            'peminjaman' => $peminjaman,
            'loan_code' => $loanCode
        ];

        return view($role . '/peminjaman/print', $data);
    }
}
