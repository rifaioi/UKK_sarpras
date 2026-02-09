<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Pengaduan as PetugasPengaduan;

class Pengaduan extends PetugasPengaduan
{
    public function index()
    {
        $statusId = $this->request->getGet('status');
        $lokasi = $this->request->getGet('lokasi');

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
        $statusModel = new \App\Models\StatusPengaduanModel();

        $data = [
            'pengaduan' => $builder->orderBy('pengaduan.created_at', 'DESC')->findAll(),
            'statuses' => $statusModel->findAll(),
            'locations' => $locationModel->where('is_deleted', 0)->findAll(),
            'filterStatus' => $statusId,
            'filterLokasi' => $lokasi
        ];
        return view('admin/pengaduan/index', $data);
    }

    public function trash()
    {
        $builder = $this->pengaduanModel->select('pengaduan.*, users.nama_lengkap, status_pengaduan.nama_status')
                                        ->join('users', 'users.id = pengaduan.user_id')
                                        ->join('status_pengaduan', 'status_pengaduan.id = pengaduan.status_id')
                                        ->where('pengaduan.is_deleted', 1);

        $data = [
            'pengaduan' => $builder->orderBy('pengaduan.created_at', 'DESC')->findAll()
        ];
        return view('admin/pengaduan/trash', $data);
    }
    
    public function process($id)
    {
        parent::process($id);
        return redirect()->to('/admin/pengaduan')->with('success', 'Status diubah');
    }

    public function update_status()
    {
        parent::update_status();
        return redirect()->to('/admin/pengaduan')->with('success', 'Pengaduan berhasil diupdate');
    }

    public function complete($id)
    {
        parent::complete($id);
        return redirect()->to('/admin/pengaduan')->with('success', 'Status selesai');
    }
    
     public function delete($id)
    {
        $this->pengaduanModel->update($id, ['is_deleted' => 1]);
        return redirect()->to('/admin/pengaduan')->with('success', 'Pengaduan dihapus');
    }

    public function restore($id)
    {
        $this->pengaduanModel->update($id, ['is_deleted' => 0]);
        return redirect()->to('/admin/pengaduan?show_deleted=1')->with('success', 'Pengaduan berhasil dikembalikan');
    }
}
