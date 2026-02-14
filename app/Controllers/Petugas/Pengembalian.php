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
        // T2-INSPECTION: Redirect to Inspeksi Kembali
        return redirect()->to("/petugas/inspections/create/$peminjaman_id?type=kembali");
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

        $foto = null;
        $fotoFile = $this->request->getFile('foto');
        
        if ($fotoFile && $fotoFile->isValid() && !$fotoFile->hasMoved()) {
            $namaFoto = $fotoFile->getRandomName();
            $fotoFile->move(FCPATH . 'uploads/pengembalian', $namaFoto);
            $foto = 'uploads/pengembalian/' . $namaFoto;
        }
        
        $this->pengembalianModel->save([
            'peminjaman_id' => $peminjamanId,
            'tgl_pengembalian' => date('Y-m-d'),
            'kondisi_id' => $kondisiId,
            'deskripsi' => $deskripsi,
            'foto' => $foto
        ]);

        $this->peminjamanModel->skipValidation(true)->update($peminjamanId, ['status_id' => 4]);

        // T1-PINJAM-010, T1-KEMBALI-006, 007
        $peminjaman = $this->peminjamanModel->find($peminjamanId);
        if ($peminjaman) {
            $db = \Config\Database::connect();
            $db->transStart();
            
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
            
            $this->sarprasModel->skipValidation(true)->update($peminjaman['sarpras_id'], $updateData);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                log_activity('Pengembalian', 'Error Update Status', "Gagal update status sarpras id: {$peminjaman['sarpras_id']} setelah pengembalian");
            } else {
                log_activity('Pengembalian', 'Update Status Sarpras', "Update status sarpras id: {$peminjaman['sarpras_id']} menjadi $status setelah pengembalian");
            }
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
            'riwayat_pengembalian' => $this->pengembalianModel->select('pengembalian.*, peminjaman.jumlah, peminjaman.tgl_pinjam, users.nama_lengkap, sarpras.nama as nama_barang, sarpras.kode, kondisi_alat.nama_kondisi')
                                                               ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                                               ->join('users', 'users.id = peminjaman.user_id')
                                                               ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                               ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                                                               ->orderBy('pengembalian.id', 'DESC')
                                                               ->findAll()
        ];
        return view($role . '/pengembalian/riwayat', $data);
    }

    public function detail($pengembalian_id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        
        $pengembalian = $this->pengembalianModel->select('pengembalian.*, peminjaman.id as peminjaman_id, peminjaman.jumlah, peminjaman.tgl_pinjam, users.nama_lengkap, sarpras.nama as nama_barang, sarpras.kategori_id, kondisi_alat.nama_kondisi')
                                                ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
                                                ->join('users', 'users.id = peminjaman.user_id')
                                                ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                                ->join('kondisi_alat', 'kondisi_alat.id = pengembalian.kondisi_id')
                                                ->find($pengembalian_id);

        if (!$pengembalian) {
            return redirect()->to("/$role/pengembalian/riwayat")->with('error', 'Data pengembalian tidak ditemukan');
        }

        $peminjamanId = $pengembalian['peminjaman_id'];
        
        // Fetch Inspections
        $inspeksiModel = new \App\Models\InspectionModel();
        $inspeksiChecklistModel = new \App\Models\InspectionResultModel();
        $templateModel = new \App\Models\InspectionChecklistItemModel();

        $inspeksiKeluar = $inspeksiModel->where('peminjaman_id', $peminjamanId)->where('type', 'keluar')->first();
        $inspeksiKembali = $inspeksiModel->where('peminjaman_id', $peminjamanId)->where('type', 'kembali')->first();

        $resultsKeluar = [];
        if ($inspeksiKeluar) {
            $raw = $inspeksiChecklistModel->where('inspection_id', $inspeksiKeluar['id'])->findAll();
            foreach ($raw as $r) $resultsKeluar[$r['checklist_item_id']] = $r;
        }

        $resultsKembali = [];
        if ($inspeksiKembali) {
            $raw = $inspeksiChecklistModel->where('inspection_id', $inspeksiKembali['id'])->findAll();
            foreach ($raw as $r) $resultsKembali[$r['checklist_item_id']] = $r;
        }

        $checklistTemplates = $templateModel->where('kategori_id', $pengembalian['kategori_id'])->findAll();

        $data = [
            'pengembalian' => $pengembalian,
            'inspeksiKeluar' => $inspeksiKeluar,
            'inspeksiKembali' => $inspeksiKembali,
            'resultsKeluar' => $resultsKeluar,
            'resultsKembali' => $resultsKembali,
            'checklistTemplates' => $checklistTemplates
        ];
        return view($role . '/pengembalian/detail', $data);
    }

    /**
     * List Damaged Items (Sedang Dalam Perbaikan)
     */
    public function rusak()
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        
        // T1-KEMBALI-SYNC: Base the repair list on the actual condition of units in sarpras table.
        // We join with the latest 'pengembalian' if it exists to show who returned it last, 
        // but the item stays in the list as long as sarpras.kondisi_id is 2 or 3.
        $data = [
            'barang_rusak' => $this->sarprasModel->select('sarpras.*, locations.nama_lokasi, kondisi_alat.nama_kondisi, 
                                                         latest_return.nama_lengkap as peminjam_terakhir, 
                                                         latest_return.tgl_pengembalian,
                                                         latest_return.id as pengembalian_id')
                                                 ->join('locations', 'locations.id = sarpras.location_id', 'left')
                                                 ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id', 'left')
                                                 // Join with a subquery of the latest returns per SARPRAS_ID to avoid duplicates
                                                 ->join('(SELECT p1.*, u.nama_lengkap, pj.sarpras_id 
                                                          FROM pengembalian p1 
                                                          JOIN peminjaman pj ON pj.id = p1.peminjaman_id
                                                          JOIN users u ON u.id = pj.user_id
                                                          WHERE p1.id IN (
                                                              SELECT MAX(p2.id) 
                                                              FROM pengembalian p2 
                                                              JOIN peminjaman pj2 ON pj2.id = p2.peminjaman_id 
                                                              GROUP BY pj2.sarpras_id
                                                          )) as latest_return', 
                                                         'latest_return.sarpras_id = sarpras.id', 'left')
                                                 ->whereIn('sarpras.kondisi_id', [2, 3]) // Rusak Ringan, Rusak Berat
                                                 ->orderBy('sarpras.updated_at', 'DESC')
                                                 ->findAll()
        ];
        return view($role . '/pengembalian/rusak', $data);
    }

    /**
     * Action to Restock a repaired item
     * ID passed is now SARPRAS_ID for better consistency
     */
    public function restock($id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $unit = $this->sarprasModel->find($id);

        if (!$unit) {
            return redirect()->back()->with('error', 'Unit tidak ditemukan.');
        }

        // 1. Update Sarpras Unit to Baik
        $this->sarprasModel->skipValidation(true)->update($id, [
            'kondisi_id' => 1,
            'stok' => 1,
            'status' => 'tersedia'
        ]);

        // 2. Sync Pengembalian record if this was a return-based repair
        // Mark all 'un-restocked' returns for this sarpras as restocked
        $db = \Config\Database::connect();
        $db->query("UPDATE pengembalian p 
                    JOIN peminjaman pj ON pj.id = p.peminjaman_id 
                    SET p.is_restocked = 1 
                    WHERE pj.sarpras_id = ? AND p.is_restocked = 0", [$id]);

        log_activity('Pengembalian', 'Restock Barang', "Unit {$unit['kode']} telah diperbaiki dan masuk stok kembali.");

        return redirect()->to("/$role/pengembalian/rusak")->with('success', "Unit {$unit['kode']} berhasil direstock ke kondisi Baik.");
    }

    public function scan()
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        return view($role . '/pengembalian/scan');
    }

    /**
     * Action to Scrap a damaged item
     * ID passed is SARPRAS_ID
     */
    public function scrap($id)
    {
        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        $unit = $this->sarprasModel->find($id);

        if (!$unit) {
            return redirect()->back()->with('error', 'Unit tidak ditemukan.');
        }

        // Mark as Lost/Scrapped in Sarpras
        $this->sarprasModel->skipValidation(true)->update($id, [
            'kondisi_id' => 4, // Hilang/Musnah
            'stok' => 0,
            'status' => 'hilang'
        ]);

        // Mark pengembalian records as scrapped (2)
        $db = \Config\Database::connect();
        $db->query("UPDATE pengembalian p 
                    JOIN peminjaman pj ON pj.id = p.peminjaman_id 
                    SET p.is_restocked = 2 
                    WHERE pj.sarpras_id = ? AND p.is_restocked = 0", [$id]);

        log_activity('Pengembalian', 'Musnahkan Barang', "Unit {$unit['kode']} ditandai hilang/dimusnahkan dari daftar perbaikan.");

        return redirect()->to("/$role/pengembalian/rusak")->with('success', "Unit {$unit['kode']} telah dihapus dari daftar perbaikan.");
    }
}
