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
        // Just view list
        $data = [
            'sarpras' => $this->sarprasModel->select('sarpras.*, locations.nama_lokasi, kategori_sarpras.nama as nama_kategori, kondisi_alat.nama_kondisi')
                                            ->join('locations', 'locations.id = sarpras.location_id')
                                            ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id')
                                            ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id')
                                            ->where('sarpras.is_deleted', 0)
                                            ->findAll()
        ];
        return view('petugas/sarpras/index', $data);
    }
}
