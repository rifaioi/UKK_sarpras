<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SarprasModel;
use App\Models\KategoriSarprasModel;
use App\Models\LocationModel;
use App\Models\KondisiAlatModel;

/**
 * Sarpras Controller
 * 
 * Handles management of school assets (Sarana & Prasarana).
 * Supports Master-Variant item structure.
 */
class Sarpras extends BaseController
{
    protected $sarprasModel;
    protected $categoryModel;
    protected $locationModel;
    protected $kondisiModel;

    public function __construct()
    {
        $this->sarprasModel = new SarprasModel();
        $this->categoryModel = new KategoriSarprasModel();
        $this->locationModel = new LocationModel();
        $this->kondisiModel = new KondisiAlatModel();
    }

    /**
     * Display list of all assets as a flat list
     */
    public function index()
    {
        $items = $this->sarprasModel->select('sarpras.*, kategori_sarpras.nama as nama_kategori, locations.nama_lokasi, kondisi_alat.nama_kondisi')
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left')
                                     ->join('locations', 'locations.id = sarpras.location_id', 'left')
                                     ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id', 'left')
                                     ->orderBy('kategori_sarpras.nama', 'ASC')
                                     ->orderBy('sarpras.nama', 'ASC')
                                     ->findAll();
        
        $data = ['items' => $items];
        return view('admin/sarpras/index', $data);
    }

    /**
     * Show create item form
     */
    public function create()
    {
        $data = [
            'categories' => $this->categoryModel->where('is_deleted', 0)->findAll(),
            'locations' => $this->locationModel->findAll(),
            'conditions' => $this->kondisiModel->findAll(),
        ];
        return view('admin/sarpras/form', $data);
    }

    /**
     * Store a new asset
     */
    public function store()
    {
        $nama = $this->request->getVar('nama');
        
        // Generate code based on the Category (kategori_id)
        $alatId = $this->request->getVar('kategori_id');
        $alat = $this->categoryModel->find($alatId);
        $kode = $this->generateKode($alat['nama']);

        $saveData = [
            'kode' => $kode,
            'nama' => $nama,
            'kategori_id' => $this->request->getVar('kategori_id'),
            'location_id' => $this->request->getVar('location_id'),
            'stok' => $this->request->getVar('stok'),
            'kondisi_id' => $this->request->getVar('kondisi_id'),
        ];

        if (!$this->sarprasModel->save($saveData)) {
            return redirect()->back()->withInput()->with('errors', $this->sarprasModel->errors());
        }
        
        log_activity('Tambah Sarpras', "Menambahkan item: $nama ($kode)");

        return redirect()->to('/admin/sarpras')->with('success', 'Data Sarpras berhasil ditambahkan');
    }

    /**
     * Generate automatic inventory code based on category name
     */
    private function generateKode($alatName)
    {
        // 3 Letters Prefix from Alat/Category
        $prefix = strtoupper(substr(str_replace(' ', '', $alatName), 0, 3));
        
        // Find highest sequence for this prefix
        $lastItem = $this->sarprasModel->where('kode LIKE', "$prefix%")
                                       ->orderBy('kode', 'DESC')
                                       ->first();
        
        if (!$lastItem) {
            return $prefix . '01';
        }
        
        // Extract number suffix
        $lastNumber = (int) substr($lastItem['kode'], 3);
        $nextNumber = str_pad($lastNumber + 1, 2, '0', STR_PAD_LEFT);
        
        return $prefix . $nextNumber;
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $data = [
            'item' => $this->sarprasModel->find($id),
            'categories' => $this->categoryModel->where('is_deleted', 0)->findAll(),
            'locations' => $this->locationModel->findAll(),
            'conditions' => $this->kondisiModel->findAll(),
        ];
        return view('admin/sarpras/form', $data);
    }

    /**
     * Update an asset
     */
    public function update($id)
    {
        $updateData = [
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
            'kategori_id' => $this->request->getVar('kategori_id'),
            'location_id' => $this->request->getVar('location_id'),
            'stok' => $this->request->getVar('stok'),
            'kondisi_id' => $this->request->getVar('kondisi_id'),
        ];

        if (!$this->sarprasModel->save($updateData)) {
            return redirect()->back()->withInput()->with('errors', $this->sarprasModel->errors());
        }

        log_activity('Update Sarpras', "Update item id: $id");

        return redirect()->to('/admin/sarpras')->with('success', 'Data Sarpras berhasil diupdate');
    }

    public function delete($id)
    {
        $this->sarprasModel->delete($id);
        log_activity('Hapus Sarpras', "Hapus/Soft delete item id: $id");
        return redirect()->to('/admin/sarpras')->with('success', 'Data Sarpras berhasil dihapus');
    }
}
