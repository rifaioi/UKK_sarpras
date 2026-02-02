<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\PengaduanModel;

class History extends BaseController
{
    protected $peminjamanModel;
    protected $pengaduanModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->pengaduanModel = new PengaduanModel();
    }

    public function index()
    {
        $userId = session()->get('id');

        $data = [
            'my_borrowings' => $this->peminjamanModel->select('peminjaman.*, sarpras.nama as nama_barang, status_peminjaman.nama_status')
                                                     ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                     ->join('status_peminjaman', 'status_peminjaman.id = peminjaman.status_id')
                                                     ->where('peminjaman.user_id', $userId)
                                                     ->orderBy('peminjaman.created_at', 'DESC')
                                                     ->findAll(),
            'my_complaints' => $this->pengaduanModel->select('pengaduan.*, status_pengaduan.nama_status')
                                                    ->join('status_pengaduan', 'status_pengaduan.id = pengaduan.status_id')
                                                    ->where('pengaduan.user_id', $userId)
                                                    ->orderBy('pengaduan.created_at', 'DESC')
                                                    ->findAll()
        ];
        
        return view('member/history/index', $data);
    }
}
