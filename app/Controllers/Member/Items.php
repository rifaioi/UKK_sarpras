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
        // Group items by name and category to show a summary for members
        $items = $this->sarprasModel->select("sarpras.nama, sarpras.kategori_id, MAX(sarpras.id) as id, kategori_sarpras.nama as nama_kategori, locations.nama_lokasi, 
                                             COUNT(*) as total_unit, 
                                             SUM(CASE WHEN sarpras.status = 'tersedia' THEN 1 ELSE 0 END) as tersedia")
                                     ->join('locations', 'locations.id = sarpras.location_id')
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
                                     ->where('sarpras.is_deleted', 0)
                                     ->where('sarpras.kondisi_id', 1) // Only Good condition base items usually
                                     ->groupBy('sarpras.nama, sarpras.kategori_id, sarpras.location_id')
                                     ->having('tersedia >', 0) // T1-PINJAM-001: Only show available
                                     ->findAll();

        $data = [
            'items' => $items
        ];

        return view('member/items/index', $data);
    }
}
