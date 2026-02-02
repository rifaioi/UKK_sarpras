<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\SarprasModel;
use App\Models\KategoriSarprasModel;

class Items extends BaseController
{
    protected $sarprasModel;

    public function __construct()
    {
        $this->sarprasModel = new SarprasModel();
    }

    public function index()
    {
        // Get all items that have stock and are not deleted
        $items = $this->sarprasModel->select('sarpras.*, locations.nama_lokasi, kategori_sarpras.nama as nama_kategori, kondisi_alat.nama_kondisi')
                                     ->join('locations', 'locations.id = sarpras.location_id')
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
                                     ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id')
                                     ->where('sarpras.is_deleted', 0)
                                     ->where('sarpras.stok >', 0)
                                     ->where('sarpras.kondisi_id', 1) // Only Good condition items
                                     ->findAll();

        $data = [
            'items' => $items
        ];

        return view('member/items/index', $data);
    }
}
