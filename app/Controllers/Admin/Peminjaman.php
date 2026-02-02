<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Peminjaman as PetugasPeminjaman;

class Peminjaman extends PetugasPeminjaman
{
    // Inherit everything. If we need to override views, we can do it here.
    // For now, let's reuse the logic but point to admin views if we want, 
    // or just use the petugas views but layout needs to be admin.
    
    /**
     * Admin: Approval dashboard
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
        return view('admin/peminjaman/index', $data);
    }
    
    /**
     * Approve a loan (wraps parent logic)
     */
    public function approve($id)
    {
        parent::approve($id);
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman disetujui');
    }

    /**
     * Reject a loan (wraps parent logic)
     */
    public function reject($id)
    {
        parent::reject($id);
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman ditolak');
    }

    /**
     * Delete a loan record (for cleanup)
     */
    public function delete($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        if (!$peminjaman) {
             return redirect()->to('/admin/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        // Forbid deleting active loans to maintain data integrity
        if ($peminjaman['status_id'] == 2) {
             return redirect()->to('/admin/peminjaman')->with('error', 'Peminjaman masih berjalan (Disetujui). Silahkan proses pengembalian terlebih dahulu.');
        }

        $this->peminjamanModel->delete($id);
        log_activity('Hapus Peminjaman', "Menghapus data peminjaman id: $id");
        return redirect()->to('/admin/peminjaman')->with('success', 'Data peminjaman dihapus');
    }
}
