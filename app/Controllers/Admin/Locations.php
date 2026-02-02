<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LocationModel;

class Locations extends BaseController
{
    protected $locationModel;

    public function __construct()
    {
        $this->locationModel = new LocationModel();
    }

    public function index()
    {
        $data = [
            'locations' => $this->locationModel->findAll()
        ];
        return view('admin/locations/index', $data);
    }

    public function create()
    {
        return view('admin/locations/form');
    }

    public function store()
    {
        $rules = [
            'nama_lokasi' => 'required|min_length[3]|is_unique[locations.nama_lokasi]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->locationModel->save([
            'nama_lokasi' => $this->request->getVar('nama_lokasi'),
            'keterangan' => $this->request->getVar('keterangan'),
        ]);

        return redirect()->to('/admin/locations')->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'location' => $this->locationModel->find($id)
        ];
        return view('admin/locations/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama_lokasi' => "required|min_length[3]|is_unique[locations.nama_lokasi,id,{$id}]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->locationModel->save([
            'id' => $id,
            'nama_lokasi' => $this->request->getVar('nama_lokasi'),
            'keterangan' => $this->request->getVar('keterangan'),
        ]);

        return redirect()->to('/admin/locations')->with('success', 'Lokasi berhasil diupdate');
    }

    public function delete($id)
    {
        $this->locationModel->delete($id);
        return redirect()->to('/admin/locations')->with('success', 'Lokasi berhasil dihapus');
    }
}
