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
    
    /**
     * Approve a loan (wraps parent logic)
     */
    public function approve($id)
    {
        parent::approve($id);
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman disetujui');
    }

    /**
     * Reject a loan with optional reason
     */
    public function reject($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);
        
        if (!$peminjaman) {
            return redirect()->to('/admin/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        // Get rejection reason from POST (optional)
        $rejectionReason = $this->request->getPost('rejection_reason');

        // Update status to rejected (3) and save reason
        $this->peminjamanModel->update($id, [
            'status_id' => 3,
            'rejection_reason' => $rejectionReason
        ]);

        log_activity('Tolak Peminjaman', "Menolak peminjaman id: $id" . ($rejectionReason ? " dengan alasan: $rejectionReason" : ""));
        
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman ditolak');
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
        
        if (!$peminjaman) {
            return redirect()->to('/admin/peminjaman')->with('error', 'Data tidak ditemukan');
        }

        // Use the generated kode_peminjaman
        $loanCode = $peminjaman['kode_peminjaman'] ?? ($peminjaman['kode'] . '-' . str_pad($id, 5, '0', STR_PAD_LEFT));

        $data = [
            'peminjaman' => $peminjaman,
            'loan_code' => $loanCode
        ];

        return view('admin/peminjaman/print', $data);
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

    /**
     * View soft-deleted loans
     */
    public function archived()
    {
        $data = [
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, status_peminjaman.nama_status')
                                                  ->join('users', 'users.id = peminjaman.user_id')
                                                  ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                  ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id')
                                                  ->onlyDeleted()
                                                  ->orderBy('peminjaman.deleted_at', 'DESC')
                                                  ->findAll()
        ];
        return view('admin/peminjaman/archived', $data);
    }

    /**
     * Restore a soft-deleted loan
     */
    public function restore($id)
    {
        // First check if it's actually deleted
        $peminjaman = $this->peminjamanModel->onlyDeleted()->find($id);
        
        if (!$peminjaman) {
            return redirect()->to('/admin/peminjaman/archived')->with('error', 'Data tidak ditemukan di arsip.');
        }

        // Restore
        $this->peminjamanModel->update($id, ['deleted_at' => null]);
        
        log_activity('Restore Peminjaman', "Mengembalikan data peminjaman id: $id dari arsip");
        return redirect()->to('/admin/peminjaman/archived')->with('success', 'Data peminjaman berhasil dipulihkan.');
    }
}
