<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriSarprasModel;
use App\Models\InspectionChecklistItemModel;

class InspectionTemplates extends BaseController
{
    protected $kategoriModel;
    protected $checklistItemModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriSarprasModel();
        $this->checklistItemModel = new InspectionChecklistItemModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Checklist Pemeriksaan',
            'categories' => $this->kategoriModel->where('is_deleted', 0)->findAll()
        ];
        return view('admin/inspection_templates/index', $data);
    }

    public function manage($kategori_id)
    {
        $category = $this->kategoriModel->find($kategori_id);
        if (!$category) {
            return redirect()->to('admin/inspection-templates')->with('error', 'Kategori tidak ditemukan');
        }

        $items = $this->checklistItemModel->where('kategori_id', $kategori_id)
                                          ->where('is_deleted', 0)
                                          ->findAll();
        
        $data = [
            'title' => 'Kelola Checklist: ' . $category['nama'],
            'category' => $category,
            'items' => $items
        ];
        return view('admin/inspection_templates/manage', $data);
    }

    public function trash_items($kategori_id)
    {
        $category = $this->kategoriModel->find($kategori_id);
        if (!$category) {
            return redirect()->to('admin/inspection-templates')->with('error', 'Kategori tidak ditemukan');
        }

        $items = $this->checklistItemModel->where('kategori_id', $kategori_id)
                                          ->where('is_deleted', 1)
                                          ->findAll();
        
        $data = [
            'title' => 'Recycle Bin Checklist: ' . $category['nama'],
            'category' => $category,
            'items' => $items
        ];
        return view('admin/inspection_templates/trash_items', $data);
    }

    public function store()
    {
        $kategori_id = $this->request->getPost('kategori_id');
        $nama_item = $this->request->getPost('nama_item');

        if (!$this->validate([
            'nama_item' => 'required|min_length[3]'
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->checklistItemModel->save([
            'kategori_id' => $kategori_id,
            'nama_item' => $nama_item
        ]);

        return redirect()->to('admin/inspection-templates/manage/' . $kategori_id)->with('success', 'Item checklist berhasil ditambahkan');
    }

    public function delete($id)
    {
        $this->checklistItemModel->update($id, ['is_deleted' => 1]);
        return redirect()->back()->with('success', 'Item checklist berhasil dihapus');
    }

    public function restore($id)
    {
        $this->checklistItemModel->update($id, ['is_deleted' => 0]);
        return redirect()->back()->with('success', 'Item checklist berhasil dikembalikan');
    }
}
