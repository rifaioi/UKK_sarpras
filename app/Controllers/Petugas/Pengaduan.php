<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\PengaduanModel;
use App\Models\StatusPengaduanModel;

class Pengaduan extends BaseController
{
    protected $pengaduanModel;
    protected $statusModel;

    public function __construct()
    {
        $this->pengaduanModel = new PengaduanModel();
        $this->statusModel = new StatusPengaduanModel();
    }

    public function index()
    {
        $data = [
            'pengaduan' => $this->pengaduanModel->select('pengaduan.*, users.nama_lengkap, status_pengaduan.nama_status')
                                                ->join('users', 'users.id = pengaduan.user_id')
                                                ->join('status_pengaduan', 'status_pengaduan.id = pengaduan.status_id')
                                                ->orderBy('pengaduan.created_at', 'DESC')
                                                ->findAll()
        ];
        return view('petugas/pengaduan/index', $data);
    }

    public function update_status()
    {
        $id = $this->request->getVar('id');
        $statusId = $this->request->getVar('status_id');
        $catatan = $this->request->getVar('catatan');
        
        $this->pengaduanModel->save([
            'id' => $id,
            'status_id' => $statusId,
            'catatan' => $catatan
        ]);
        
        log_activity('Update Pengaduan', 'Status pengaduan id '.$id.' diubah');
        
        return redirect()->back()->with('success', 'Pengaduan berhasil diupdate');
    }
    
    public function process($id)
    {
        $this->pengaduanModel->update($id, ['status_id' => 2]); // Sedang Diproses
        return redirect()->back()->with('success', 'Status diubah menjadi Sedang Diproses');
    }

    public function complete($id)
    {
        $this->pengaduanModel->update($id, ['status_id' => 3]); // Selesai
        return redirect()->back()->with('success', 'Status diubah menjadi Selesai');
    }
    
    public function delete($id)
    {
         $this->pengaduanModel->delete($id);
         log_activity('Hapus Pengaduan', "Menghapus pengaduan id: $id");
         return redirect()->to('/petugas/pengaduan')->with('success', 'Pengaduan dihapus');
    }
}
