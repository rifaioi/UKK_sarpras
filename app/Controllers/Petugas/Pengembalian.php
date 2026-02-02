<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\PengembalianModel;
use App\Models\SarprasModel;
use App\Models\KondisiAlatModel;

class Pengembalian extends BaseController
{
    protected $peminjamanModel;
    protected $pengembalianModel;
    protected $sarprasModel;
    protected $kondisiModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->pengembalianModel = new PengembalianModel();
        $this->sarprasModel = new SarprasModel();
        $this->kondisiModel = new KondisiAlatModel();
    }

    public function index()
    {
        // Show active borrwings (Status 2: Disetujui/Dipinjam)
        $data = [
            'active_peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang')
                                                         ->join('users', 'users.id = peminjaman.user_id')
                                                         ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                         ->where('peminjaman.status_id', 2)
                                                         ->findAll()
        ];
        return view('petugas/pengembalian/index', $data);
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
        return view('petugas/pengembalian/form', $data);
    }

    public function store()
    {
        $peminjamanId = $this->request->getVar('peminjaman_id');
        $kondisiId = $this->request->getVar('kondisi_id');
        $deskripsi = $this->request->getVar('deskripsi');
        
        // Handle file upload
        $foto = null;
        $fotoFile = $this->request->getFile('foto');
        
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move('uploads/pengembalian', $namaFoto);
            $foto = $namaFoto;
        }
        
        // 1. Save to pengembalian table
        $this->pengembalianModel->save([
            'peminjaman_id' => $peminjamanId,
            'tgl_pengembalian' => date('Y-m-d'),
            'kondisi_id' => $kondisiId,
            'deskripsi' => $deskripsi,
            'foto' => $foto
        ]);

        // 2. Update peminjaman status to 4 (Dikembalikan)
        $this->peminjamanModel->update($peminjamanId, ['status_id' => 4]);

        // 3. Update stock (Add back)
        // Only add back to stock if condition is 'Baik' (1)
        // If damaged/lost, checking it back in effectively keeps it out of circulation (stock not increased)
        
        if ($kondisiId == 1) {
             $peminjaman = $this->peminjamanModel->find($peminjamanId);
             $item = $this->sarprasModel->find($peminjaman['sarpras_id']);
             
             $newStock = $item['stok'] + $peminjaman['jumlah'];
             $this->sarprasModel->update($item['id'], ['stok' => $newStock]);
        }

        return redirect()->to('/petugas/pengembalian')->with('success', 'Pengembalian berhasil diproses');
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
        return view('petugas/pengembalian/riwayat', $data);
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
            return redirect()->to('/petugas/pengembalian/riwayat')->with('error', 'Data pengembalian tidak ditemukan');
        }

        $data = ['pengembalian' => $pengembalian];
        return view('petugas/pengembalian/detail', $data);
    }
}
