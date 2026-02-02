<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Pengaduan as PetugasPengaduan;

class Pengaduan extends PetugasPengaduan
{
    public function index()
    {
        $data = [
            'pengaduan' => $this->pengaduanModel->select('pengaduan.*, users.nama_lengkap, status_pengaduan.nama_status')
                                                ->join('users', 'users.id = pengaduan.user_id')
                                                ->join('status_pengaduan', 'status_pengaduan.id = pengaduan.status_id')
                                                ->orderBy('pengaduan.created_at', 'DESC')
                                                ->findAll()
        ];
        return view('admin/pengaduan/index', $data);
    }
    
    public function process($id)
    {
        parent::process($id);
        return redirect()->to('/admin/pengaduan')->with('success', 'Status diubah');
    }

    public function complete($id)
    {
        parent::complete($id);
        return redirect()->to('/admin/pengaduan')->with('success', 'Status selesai');
    }
    
     public function delete($id)
    {
        parent::delete($id);
        return redirect()->to('/admin/pengaduan')->with('success', 'Pengaduan dihapus');
    }
}
