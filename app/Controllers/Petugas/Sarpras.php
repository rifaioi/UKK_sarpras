<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\SarprasModel;

class Sarpras extends BaseController
{
    protected $sarprasModel;

    public function __construct()
    {
        $this->sarprasModel = new SarprasModel();
    }

    public function index()
    {
        $q = $this->request->getGet('q');
        
        $query = $this->sarprasModel->select("sarpras.nama, sarpras.kategori_id, kategori_sarpras.nama as nama_kategori, 
                                             COUNT(*) as total_unit, 
                                             SUM(CASE WHEN sarpras.status = 'tersedia' THEN 1 ELSE 0 END) as tersedia,
                                             MIN(sarpras.id) as id")
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left')
                                     ->where('sarpras.is_deleted', 0);

        if ($q) {
            $query->like('sarpras.nama', $q);
        }

        $items = $query->groupBy('sarpras.nama, sarpras.kategori_id')
                       ->orderBy('kategori_sarpras.nama', 'ASC')
                       ->orderBy('sarpras.nama', 'ASC')
                       ->findAll();
        
        $data = [
            'items' => $items,
            'q' => $q
        ];
        return view('petugas/sarpras/index', $data);
    }

    public function units()
    {
        $nama = $this->request->getGet('nama');
        $kategoriId = $this->request->getGet('kategori_id');

        if (!$nama) {
            return redirect()->to('/petugas/sarpras');
        }

        $items = $this->sarprasModel->select('sarpras.*, kategori_sarpras.nama as nama_kategori, locations.nama_lokasi, kondisi_alat.nama_kondisi')
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left')
                                     ->join('locations', 'locations.id = sarpras.location_id', 'left')
                                     ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id', 'left')
                                     ->where('sarpras.nama', $nama)
                                     ->where('sarpras.kategori_id', $kategoriId)
                                     ->orderBy('kode', 'ASC')
                                     ->findAll();

        $data = [
            'items' => $items,
            'nama_barang' => $nama
        ];
        return view('petugas/sarpras/units', $data);
    }

    public function show($id)
    {
        $item = $this->sarprasModel->select('sarpras.*, kategori_sarpras.nama as nama_kategori, locations.nama_lokasi, kondisi_alat.nama_kondisi')
                                   ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left')
                                   ->join('locations', 'locations.id = sarpras.location_id', 'left')
                                   ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id', 'left')
                                   ->find($id);

        if (!$item) {
            return redirect()->to('/petugas/sarpras')->with('error', 'Data barang tidak ditemukan.');
        }

        $data = ['item' => $item];
        return view('petugas/sarpras/detail', $data);
    }
}
