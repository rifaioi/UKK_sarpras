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
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        // Show active borrwings (Status 2: Disetujui/Dipinjam)
        $data = [
            'active_peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang')
                                                         ->join('users', 'users.id = peminjaman.user_id')
                                                         ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                         ->where('peminjaman.status_id', 2)
                                                         ->findAll()
        ];
        return view($role . '/pengembalian/index', $data);
    }

    public function form($peminjaman_id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'peminjaman' => $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap, sarpras.nama as nama_barang, sarpras.stok as current_stock')
                                                  ->join('users', 'users.id = peminjaman.user_id')
                                                  ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                  ->find($peminjaman_id),
            'conditions' => $this->kondisiModel->findAll()
        ];
        return view($role . '/pengembalian/form', $data);
    }

    public function store()
    {
        $peminjamanId = $this->request->getVar('peminjaman_id');
        $kondisiId = $this->request->getVar('kondisi_id');
        $deskripsi = $this->request->getVar('deskripsi');
        
        // T1-KEMBALI-003: Mandatory description if not "Baik" (ID 1)
        if ($kondisiId != 1 && empty(trim($deskripsi))) {
            return redirect()->back()->withInput()->with('error', 'Deskripsi kerusakan/masalah wajib diisi jika kondisi tidak Baik.');
        }

        // Handle file upload (T1-KEMBALI-004)
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

        // 3. Update unit status and stock (T1-PINJAM-010, T1-KEMBALI-006, 007)
        $peminjaman = $this->peminjamanModel->find($peminjamanId);
        if ($peminjaman) {
            $status = 'tersedia';
            if ($kondisiId == 4) {
                $status = 'hilang';
            } elseif ($kondisiId == 2 || $kondisiId == 3) {
                $status = 'rusak';
            }

            $updateData = [
                'kondisi_id' => $kondisiId,
                'stok' => ($kondisiId == 1 ? 1 : 0),
                'status' => $status
            ];
            $this->sarprasModel->update($peminjaman['sarpras_id'], $updateData);
        }

        $rolePath = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $redirectUrl = "/$rolePath/pengembalian";
        
        if ($kondisiId == 1) {
             return redirect()->to($redirectUrl)->with('success', 'Pengembalian berhasil. Barang dalam kondisi Baik & stok tersedia kembali.');
        } elseif ($kondisiId == 4) {
             return redirect()->to($redirectUrl)->with('success', 'Pengembalian diproses. Barang ditandai HILANG & stok dinonaktifkan.');
        } else {
             return redirect()->to($redirectUrl)->with('success', 'Pengembalian berhasil. Barang ditandai RUSAK & stok dinonaktifkan.');
        }
    }

    public function riwayat()
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
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
        return view($role . '/pengembalian/riwayat', $data);
    }

    public function detail($pengembalian_id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        // Show detailed return record with damage report and photo
        $pengembalian = $this->pengembalianModel->select('pengembalian.*, peminjaman.jumlah, peminjaman.tgl_pinjam, users.nama_lengkap, sarpras.nama as nama_barang, kondisi_alat.nama_kondisi')
                                                ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                                ->join('users', 'users.id = peminjaman.user_id')
                                                ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                                                ->find($pengembalian_id);

        if (!$pengembalian) {
            return redirect()->to("/$role/pengembalian/riwayat")->with('error', 'Data pengembalian tidak ditemukan');
        }

        $data = ['pengembalian' => $pengembalian];
        return view($role . '/pengembalian/detail', $data);
    }

    /**
     * List Damaged Items (Sedang Dalam Perbaikan)
     */
    public function rusak()
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $data = [
            'barang_rusak' => $this->pengembalianModel->select('pengembalian.*, peminjaman.jumlah, users.nama_lengkap, sarpras.nama as nama_barang, kondisi_alat.nama_kondisi')
                                                      ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                                      ->join('users', 'users.id = peminjaman.user_id')
                                                      ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                      ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                                                      ->where('pengembalian.kondisi_id', 2) // Specifically 'Rusak'
                                                      ->where('pengembalian.is_restocked', 0) // Not yet repaired
                                                      ->orderBy('pengembalian.tgl_pengembalian', 'DESC')
                                                      ->findAll()
        ];
        return view($role . '/pengembalian/rusak', $data);
    }

    /**
     * Action to Restock a repaired item
     */
    public function restock($id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $pengembalian = $this->pengembalianModel->find($id);

        if (!$pengembalian || $pengembalian['is_restocked'] == 1) {
            return redirect()->back()->with('error', 'Data tidak valid atau sudah direstock.');
        }

        $peminjaman = $this->peminjamanModel->find($pengembalian['peminjaman_id']);
        if ($peminjaman) {
            // Update unit to Baik (1) and Stok 1
            $this->sarprasModel->update($peminjaman['sarpras_id'], [
                'kondisi_id' => 1,
                'stok' => 1
            ]);
            
            // Mark as restocked
            $this->pengembalianModel->update($id, ['is_restocked' => 1]);
            
            log_activity('Restock Barang Rusak', "Restock unit id: {$peminjaman['sarpras_id']} dari perbaikan.");
        }

        return redirect()->to("/$role/pengembalian/rusak")->with('success', 'Barang berhasil diperbaiki dan stok tersedia kembali.');
    }

    /**
     * Display QR Scanner for return
     */
    public function scan()
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        return view($role . '/pengembalian/scan');
    }

    /**
     * Action to Scrap a damaged item
     */
    public function scrap($id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $this->pengembalianModel->update($id, ['is_restocked' => 2]);
        return redirect()->to("/$role/pengembalian/rusak")->with('success', 'Data barang rusak telah dihapus (Barang dimusnahkan).');
    }
}
