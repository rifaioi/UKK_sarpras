<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriSarprasModel;

class Categories extends BaseController
{
    protected $categoryModel;

    public function __construct()
    {
        $this->categoryModel = new KategoriSarprasModel();
    }

    public function index()
    {
        $data = [
            'categories' => $this->categoryModel->where('is_deleted', 0)
                                                ->orderBy('nama', 'ASC')
                                                ->findAll()
        ];
        return view('admin/categories/index', $data);
    }

    public function create()
    {
        return view('admin/categories/form');
    }

    public function store()
    {
        $rules = [
            'nama' => 'required|min_length[3]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'nama' => $this->request->getVar('nama'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data = [
            'category' => $this->categoryModel->find($id),
        ];
        return view('admin/categories/form', $data);
    }

    public function update($id)
    {
        $rules = [
            'nama' => "required|min_length[3]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diupdate');
    }

    public function delete($id)
    {
        $this->categoryModel->update($id, ['is_deleted' => 1]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus');
    }
}
