<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriSarprasModel;

class Categories extends BaseController
{
    protected $categoryModel;
    protected $sarprasModel;

    public function __construct()
    {
        $this->categoryModel = new KategoriSarprasModel();
        $this->sarprasModel = new \App\Models\SarprasModel();
    }

    public function index()
    {
        $data = [
            'categories' => $this->categoryModel->where('is_deleted', 0)->orderBy('nama', 'ASC')->findAll()
        ];
        return view('admin/categories/index', $data);
    }

    public function trash()
    {
        $data = [
            'categories' => $this->categoryModel->where('is_deleted', 1)->orderBy('nama', 'ASC')->findAll()
        ];
        return view('admin/categories/trash', $data);
    }

    public function create()
    {
        return view('admin/categories/form');
    }

    public function store()
    {
        $rules = [
            'nama' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'nama' => $this->request->getVar('nama'),
            'expected_lifespan' => $this->request->getVar('expected_lifespan') ?? 5,
            'is_deleted' => 0
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
            'nama' => "required",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->categoryModel->save([
            'id' => $id,
            'nama' => $this->request->getVar('nama'),
            'expected_lifespan' => $this->request->getVar('expected_lifespan'),
        ]);

        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil diupdate');
    }

    public function delete($id)
    {
        // Check if there are assets in this category
        $hasAssets = $this->sarprasModel->where('kategori_id', $id)
                                        ->where('is_deleted', 0)
                                        ->countAllResults();

        if ($hasAssets > 0) {
            return redirect()->back()->with('error', 'Kategori tidak bisa dihapus karena masih memiliki barang di dalamnya.');
        }

        $this->categoryModel->update($id, ['is_deleted' => 1]);
        return redirect()->to('/admin/categories')->with('success', 'Kategori berhasil dihapus');
    }

    public function restore($id)
    {
        $this->categoryModel->update($id, ['is_deleted' => 0]);
        return redirect()->to('/admin/categories?show_deleted=1')->with('success', 'Kategori berhasil dikembalikan');
    }
}
