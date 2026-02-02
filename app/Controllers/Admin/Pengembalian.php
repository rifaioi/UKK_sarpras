<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\Pengembalian as PetugasPengembalian;

class Pengembalian extends PetugasPengembalian
{
    public function index()
    {
         $data = [
            'active_peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang')
                                                         ->join('users', 'users.id = peminjaman.user_id')
                                                         ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                         ->where('peminjaman.status_id', 2)
                                                         ->findAll()
        ];
        return view('admin/pengembalian/index', $data);
    }

    public function form($peminjaman_id)
    {
        $data = [
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, sarpras.stok as current_stock')
                                                  ->join('users', 'users.id = peminjaman.user_id')
                                                  ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                  ->find($peminjaman_id),
            'conditions' => $this->kondisiModel->findAll()
        ];
        return view('admin/pengembalian/form', $data);
    }

    public function store()
    {
        $res = parent::store();
        return redirect()->to('/admin/pengembalian')->with('success', 'Pengembalian berhasil diproses');
    }

    public function riwayat()
    {
        // Show all completed returns with condition and equipment info
        $data = [
            'riwayat_pengembalian' => $this->pengembalianModel->select('pengembalian.*, peminjaman.jumlah, peminjaman.tgl_pinjam, users.nama_lengkap, sarpras.nama as nama_barang, kondisi_alat.nama_kondisi')
                                                               ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                                               ->join('users', 'users.id = peminjaman.user_id')
                                                               ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                               ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                                                               ->orderBy('pengembalian.tgl_pengembalian', 'DESC')
                                                               ->findAll()
        ];
        return view('admin/pengembalian/riwayat', $data);
    }

    public function detail($pengembalian_id)
    {
        // Show detailed return record with damage report and photo
        $pengembalian = $this->pengembalianModel->select('pengembalian.*, peminjaman.jumlah, peminjaman.tgl_pinjam, users.nama_lengkap, sarpras.nama as nama_barang, kondisi_alat.nama_kondisi')
                                                ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                                ->join('users', 'users.id = peminjaman.user_id')
                                                ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                                                ->find($pengembalian_id);

        if (!$pengembalian) {
            return redirect()->to('/admin/pengembalian/riwayat')->with('error', 'Data pengembalian tidak ditemukan');
        }

        $data = ['pengembalian' => $pengembalian];
        return view('admin/pengembalian/detail', $data);
    }
}
