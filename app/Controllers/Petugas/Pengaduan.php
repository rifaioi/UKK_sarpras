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
        $statusId = $this->request->getVar('status');
        $lokasi = $this->request->getVar('lokasi');

        $builder = $this->pengaduanModel->select('pengaduan.*, users.nama_lengkap, status_pengaduan.nama_status')
                                        ->join('users', 'users.id = pengaduan.user_id')
                                        ->join('status_pengaduan', 'status_pengaduan.id = pengaduan.status_id')
                                        ->where('pengaduan.is_deleted', 0);

        if (!empty($statusId)) {
            $builder->where('pengaduan.status_id', $statusId);
        }

        if (!empty($lokasi)) {
            $builder->where('pengaduan.lokasi', $lokasi);
        }

        $locationModel = new \App\Models\LocationModel();
        
        $data = [
            'pengaduan' => $builder->orderBy('pengaduan.created_at', 'DESC')->findAll(),
            'statuses' => $this->statusModel->findAll(),
            'locations' => $locationModel->where('is_deleted', 0)->findAll(),
            'filterStatus' => $statusId,
            'filterLokasi' => $lokasi
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
         $this->pengaduanModel->update($id, ['is_deleted' => 1]);
         log_activity('Hapus Pengaduan', "Menghapus pengaduan id: $id");
         return redirect()->to('/petugas/pengaduan')->with('success', 'Pengaduan dihapus');
    }
}
