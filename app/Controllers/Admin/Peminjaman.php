<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Peminjaman as PetugasPeminjaman;

class Peminjaman extends PetugasPeminjaman
{
    
    
    /**
     * Approve a loan (wraps parent logic)
     */
    public function approve($id)
    {
        // Call parent approval logic (reserves stock)
        // Note: We don't use the return value because we want to handle the redirect for Admin specifically
        // or actually, the parent logic does the heavy lifting.
        // But since parent returns a RedirectResponse, we should just replicate the redirect destination
        // OR better yet, let's just copy the logic or return the parent's response directly if we trust it, 
        // BUT the parent redirects to /petugas/... which we might want to change.
        
        // Let's just execute the parent logic (which is NOT just return parent::approve). 
        // Calling parent::approve($id) executes it.
        // But wait, parent::approve() returns a RedirectResponse. It doesn't just "do logic".
        // If we call it and ignore the return, the logic (DB updates) still happens.
        $response = parent::approve($id);
        
        // If the parent found an error (e.g. stock), it returned a redirect back. 
        // We should check flash data or something? 
        if (session()->getFlashdata('error')) {
            return $response;
        }
        
        // Simplest fix: Just return the redirect to the inspection page, pointing to the Petugas controller (since we share it)
        // or creating an Admin route for inspection.
        // Redirect to the Admin route for inspection
        return redirect()->to('/admin/inspections/create/' . $id)->with('success', 'Silakan lakukan inspeksi kondisi barang.');
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

        $rejectionReason = trim($this->request->getPost('rejection_reason'));

        if (empty($rejectionReason) || strlen($rejectionReason) < 20) {
            return redirect()->back()->withInput()->with('error', 'Alasan penolakan wajib diisi (minimal 20 karakter).');
        }

        if ($peminjaman['status_id'] == 2) {
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

        log_activity('Peminjaman', 'Tolak Peminjaman', "Menolak peminjaman id: $id dengan alasan: $rejectionReason");
        
        return redirect()->to('/admin/peminjaman')->with('success', 'Peminjaman ditolak dan stok dikembalikan');
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
        log_activity('Peminjaman', 'Hapus Peminjaman', "Menghapus data peminjaman id: $id");
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

    public function restore($id)
    {
        // First check if it's actually deleted
        $peminjaman = $this->peminjamanModel->onlyDeleted()->find($id);
        
        if (!$peminjaman) {
            return redirect()->to('/admin/peminjaman/archived')->with('error', 'Data tidak ditemukan di arsip.');
        }

        // Restore
        $this->peminjamanModel->update($id, ['deleted_at' => null]);
        
        log_activity('Peminjaman', 'Restore Peminjaman', "Mengembalikan data peminjaman id: $id dari arsip");
        return redirect()->to('/admin/peminjaman/archived')->with('success', 'Data peminjaman berhasil dipulihkan.');
    }
}
