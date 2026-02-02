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
     * Display list of loan requests
     */
    public function index()
    {
        $data = [
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, status_peminjaman.nama_status')
                                                  ->join('users', 'users.id = peminjaman.user_id')
                                                  ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                  ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id')
                                                  ->orderBy('peminjaman.created_at', 'DESC')
                                                  ->findAll()
        ];
        return view('petugas/peminjaman/index', $data);
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
        
        // 1. Reduce stock
        $newStock = (int)$item['stok'] - (int)$peminjaman['jumlah'];
        $this->sarprasModel->update($item['id'], ['stok' => $newStock]);

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
        $this->peminjamanModel->update($id, ['status_id' => 3]);
        log_activity('Reject Peminjaman', "Menolak peminjaman id: $id");
        return redirect()->to('/petugas/peminjaman')->with('success', 'Peminjaman ditolak');
    }
}
