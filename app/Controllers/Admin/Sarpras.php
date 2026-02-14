<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SarprasModel;
use App\Models\KategoriSarprasModel;
use App\Models\LocationModel;
use App\Models\KondisiAlatModel;
use App\Models\PeminjamanModel;

/**
 * Sarpras Controller
 * Handles school asset management (Master-Variant structure).
 */
class Sarpras extends BaseController
{
    protected $sarprasModel;
    protected $categoryModel;
    protected $locationModel;
    protected $kondisiModel;
    protected $peminjamanModel;

    public function __construct()
    {
        $this->sarprasModel = new SarprasModel();
        $this->categoryModel = new KategoriSarprasModel();
        $this->locationModel = new LocationModel();
        $this->kondisiModel = new KondisiAlatModel();
        $this->peminjamanModel = new PeminjamanModel();
    }

    /**
     * Display list of all assets as a flat list
     */
    public function index()
    {
        $showDeleted = $this->request->getGet('show_deleted');
        $q = $this->request->getGet('q');
        
        $query = $this->sarprasModel->select("sarpras.nama, sarpras.kategori_id, kategori_sarpras.nama as nama_kategori, 
                                             COUNT(*) as total_unit, 
                                             SUM(CASE WHEN sarpras.status = 'tersedia' THEN 1 ELSE 0 END) as tersedia,
                                             MIN(sarpras.id) as id")
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left');

        if ($showDeleted) {
            $query->where('sarpras.is_deleted', 1);
        } else {
            $query->where('sarpras.is_deleted', 0);
        }

        if ($q) {
            $query->like('sarpras.nama', $q);
        }

        $items = $query->groupBy('sarpras.nama, sarpras.kategori_id')
                       ->orderBy('kategori_sarpras.nama', 'ASC')
                       ->orderBy('sarpras.nama', 'ASC')
                       ->findAll();
        
        $data = [
            'items' => $items,
            'categories' => $this->categoryModel->where('is_deleted', 0)->findAll(),
            'q' => $q
        ];
        return view('admin/sarpras/index', $data);
    }

    public function create()
    {
        $data = [
            'categories' => $this->categoryModel->where('is_deleted', 0)->findAll(),
            'locations' => $this->locationModel->findAll(),
            'conditions' => $this->kondisiModel->findAll(),
            'all_sarpras' => $this->sarprasModel->where('is_deleted', 0)->orderBy('nama', 'ASC')->findAll(),
        ];
        return view('admin/sarpras/form', $data);
    }

    /**
     * Store new assets (supports multi-unit/batch)
     */
    public function store()
    {
        $nama = $this->request->getVar('nama');
        $kategoriId = $this->request->getVar('kategori_id');
        $locationId = $this->request->getVar('location_id');
        $jumlah = (int)$this->request->getVar('stok'); 
        $kondisiId = $this->request->getVar('kondisi_id');

        if ($jumlah < 1) {
            return redirect()->back()->withInput()->with('error', 'Jumlah barang minimal 1.');
        }

        $kategori = $this->categoryModel->find($kategoriId);
        $location = $this->locationModel->find($locationId);

        $db = \Config\Database::connect();
        $db->transStart();

        for ($i = 0; $i < $jumlah; $i++) {
            $kode = $this->generateKode($kategori['nama'], $location['nama_lokasi'], $nama);

            $status = 'tersedia';
            if ($kondisiId == 4) {
                $status = 'hilang';
            } elseif ($kondisiId == 3) { // Only Rusak Berat (3) sets status to rusak
                $status = 'rusak';
            }

            $saveData = [
                'kode' => $kode,
                'nama' => $nama,
                'kategori_id' => $kategoriId,
                'location_id' => $locationId,
                'stok' => 1, 
                'kondisi_id' => $kondisiId,
                'status' => $status,
                'maintenance_interval' => $this->request->getVar('maintenance_interval') ?: null,
                'tgl_pengadaan' => $this->request->getVar('tgl_pengadaan') ?: null,
                'harga_beli' => $this->request->getVar('harga_beli') ?: 0,
                'parent_id' => $this->request->getVar('parent_id') ?: null,
            ];

            if (!$this->sarprasModel->insert($saveData)) {
                $db->transRollback();
                $errors = $this->sarprasModel->errors();
                log_activity('Sarpras', 'Gagal CREATE', "Gagal menambahkan item: $nama", json_encode($errors));
                return redirect()->back()->withInput()->with('errors', $errors);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data sarpras.');
        }

        log_activity('Sarpras', 'CREATE', "Menambahkan $jumlah unit item: $nama");
        
        if ($this->request->getVar('redirect_to') === 'units') {
            return redirect()->to(base_url("admin/sarpras/units?nama=" . urlencode($nama) . "&kategori_id=" . $kategoriId))
                             ->with('success', "$jumlah unit $nama berhasil ditambahkan");
        }

        return redirect()->to('/admin/sarpras')->with('success', "$jumlah unit $nama berhasil ditambahkan");
    }

    /**
     * Generate automatic inventory code
     * Format: CAT-LOC-NAME5-SEQ
     * e.g., ELE-LAB-KURSI-001
     */
    private function generateKode($categoryName, $locationName, $itemName)
    {
        // Helper to clean and shorten strings
        $clean = function($str, $len) {
            return strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $str), 0, $len));
        };

        $p1 = $clean($categoryName, 3);
        $p2 = $clean($locationName, 3);
        $p3 = $clean($itemName, 5);
        
        $prefix = "$p1-$p2-$p3";
        
        // Find highest sequence for this prefix
        $lastItem = $this->sarprasModel->where('kode LIKE', "$prefix-%")
                                       ->orderBy('id', 'DESC') // Order by ID is safer for insertion order usually, but let's check kode length/value
                                       ->orderBy('kode', 'DESC')
                                       ->first();
        
        $nextNum = 1;
        if ($lastItem) {
            // Extract number from end
            $parts = explode('-', $lastItem['kode']);
            $lastSeq = end($parts);
            if (is_numeric($lastSeq)) {
                $nextNum = (int)$lastSeq + 1;
            }
        }
        
        return $prefix . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    public function edit($id)
    {
        $data = [
            'item' => $this->sarprasModel->find($id),
            'categories' => $this->categoryModel->where('is_deleted', 0)->findAll(),
            'locations' => $this->locationModel->findAll(),
            'conditions' => $this->kondisiModel->findAll(),
            'all_sarpras' => $this->sarprasModel->where('is_deleted', 0)
                                                ->where('id !=', $id)
                                                ->orderBy('nama', 'ASC')
                                                ->findAll(),
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
            'kondisi_id' => $this->request->getVar('kondisi_id'),
            'maintenance_interval' => $this->request->getVar('maintenance_interval') ?: null,
            'tgl_pengadaan' => $this->request->getVar('tgl_pengadaan') ?: null,
            'harga_beli' => $this->request->getVar('harga_beli') ?: 0,
            'parent_id' => $this->request->getVar('parent_id') ?: null,
        ];

        // Sync status based on condition (T1-PINJAM-010)
        $existing = $this->sarprasModel->find($id);
        if ($existing && $existing['status'] != 'dipinjam') {
            $kondisiId = $updateData['kondisi_id'];
            $status = 'tersedia';
            if ($kondisiId == 4) {
                $status = 'hilang';
            } elseif ($kondisiId == 3) { // Only Rusak Berat (3) sets status to rusak
                $status = 'rusak';
            }
            $updateData['status'] = $status;
            $updateData['stok'] = ($status == 'tersedia' ? 1 : 0);
        }

        if (!$this->sarprasModel->save($updateData)) {
            $errors = $this->sarprasModel->errors();
            log_activity('Sarpras', 'Gagal UPDATE', "Gagal update item id: $id", json_encode($errors));
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        log_activity('Sarpras', 'UPDATE', "Update item id: $id");

        return redirect()->to('/admin/sarpras')->with('success', 'Data Sarpras berhasil diupdate');
    }

    public function delete($id)
    {
        // Check for active or pending loans (T1-PINJAM-011)
        $activeLoans = $this->peminjamanModel->where('sarpras_id', $id)
                                              ->whereIn('status_id', [1, 2])
                                              ->countAllResults();
        
        if ($activeLoans > 0) {
            return redirect()->back()->with('error', 'Barang masih dipinjam dan tidak dapat dihapus.');
        }

        $this->sarprasModel->delete($id);
        log_activity('Sarpras', 'DELETE', "Hapus/Soft delete item id: $id");
        return redirect()->to('/admin/sarpras')->with('success', 'Data Sarpras berhasil dihapus');
    }

    public function restore($id)
    {
        $this->sarprasModel->update($id, ['deleted_at' => null]);
        log_activity('Sarpras', 'RESTORE', "Mengembalikan item id: $id");
        return redirect()->to('/admin/sarpras/trash')->with('success', 'Data Sarpras berhasil dikembalikan');
    }

    public function restore_group($kategoriId = null)
    {
        if ($kategoriId === null) {
            return redirect()->to('/admin/sarpras/trash')->with('error', 'ID Kategori tidak valid.');
        }

        $nama = $this->request->getGet('nama');
        if (!$nama) {
            return redirect()->to('/admin/sarpras/trash')->with('error', 'Gagal restore: Nama barang tidak ditemukan.');
        }

        $this->sarprasModel->set(['deleted_at' => null])
                           ->where('nama', $nama)
                           ->where('kategori_id', $kategoriId)
                           ->where('deleted_at IS NOT NULL')
                           ->update();
        
        log_activity('Sarpras', 'RESTORE', "Mengembalikan grup item: $nama");
        return redirect()->to('/admin/sarpras/trash')->with('success', "Semua unit $nama berhasil dikembalikan");
    }

    public function restore_group_redirect()
    {
        return redirect()->to('/admin/sarpras/trash')->with('error', 'ID Kategori tidak valid.');
    }

    public function trash()
    {
        $q = $this->request->getGet('q');
        
        $query = $this->sarprasModel->select("sarpras.nama, sarpras.kategori_id, kategori_sarpras.nama as nama_kategori, 
                                             COUNT(*) as total_unit, 
                                             MIN(sarpras.id) as id")
                                     ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left')
                                     ->onlyDeleted();

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
        return view('admin/sarpras/trash', $data);
    }

    public function delete_permanently($id)
    {
        // Check if item is already soft-deleted
        $item = $this->sarprasModel->onlyDeleted()->find($id);
        
        if (!$item) {
             return redirect()->to('/admin/sarpras/trash')->with('error', 'Item tidak ditemukan di sampah.');
        }

        $this->sarprasModel->delete($id, true); // true = hard delete
        log_activity('Sarpras', 'DELETE', "Menghapus permanen item id: $id");
        return redirect()->to('/admin/sarpras/trash')->with('success', 'Item berhasil dihapus permanen.');
    }

    public function delete_group($kategoriId)
    {
        $nama = $this->request->getGet('nama');
        if (!$nama) {
            return redirect()->to('/admin/sarpras')->with('error', 'Gagal menghapus: Nama barang tidak ditemukan.');
        }

        // Check if any unit in this group is currently borrowed or pending
        $activeLoans = $this->peminjamanModel->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
                                              ->where('sarpras.nama', $nama)
                                              ->where('sarpras.kategori_id', $kategoriId)
                                              ->whereIn('peminjaman.status_id', [1, 2])
                                              ->countAllResults();

        if ($activeLoans > 0) {
            return redirect()->to('/admin/sarpras')->with('error', 'Beberapa unit barang ini masih dipinjam dan tidak dapat dihapus.');
        }

        $this->sarprasModel->where('nama', $nama)
                           ->where('kategori_id', $kategoriId)
                           ->delete();
        
        log_activity('Sarpras', 'DELETE', "Hapus grup item: $nama");
        return redirect()->to('/admin/sarpras')->with('success', "Semua unit $nama berhasil dihapus");
    }

    /**
     * List all individual units of a product
     */
    public function units()
    {
        $nama = $this->request->getGet('nama');
        $kategoriId = $this->request->getGet('kategori_id');

        if (!$nama) {
            return redirect()->to('/admin/sarpras');
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
            'nama_barang' => $nama,
            'kategori_id' => $kategoriId,
            'locations' => (new LocationModel())->findAll(),
            'conditions' => (new KondisiAlatModel())->findAll()
        ];
        return view('admin/sarpras/units', $data);
    }

    /**
     * Show detail of a single asset unit
     */
    public function show($id)
    {
        $item = $this->sarprasModel->select('sarpras.*, kategori_sarpras.nama as nama_kategori, locations.nama_lokasi, kondisi_alat.nama_kondisi')
                                   ->join('kategori_sarpras', 'kategori_sarpras.id = sarpras.kategori_id', 'left')
                                   ->join('locations', 'locations.id = sarpras.location_id', 'left')
                                   ->join('kondisi_alat', 'kondisi_alat.id = sarpras.kondisi_id', 'left')
                                   ->find($id);

        if (!$item) {
            return redirect()->to('/admin/sarpras')->with('error', 'Data barang tidak ditemukan.');
        }

        $data = ['item' => $item];
        return view('admin/sarpras/detail', $data);
    }

    /**
     * Rename an entire group of assets
     */
    public function rename_group()
    {
        $oldName = $this->request->getPost('old_name');
        $newName = $this->request->getPost('new_name');
        $oldKategoriId = $this->request->getPost('kategori_id');
        $newKategoriId = $this->request->getPost('new_kategori_id');

        if (!$oldName || !$newName || !$oldKategoriId || !$newKategoriId) {
            return redirect()->back()->with('error', 'Data tidak lengkap.');
        }

        // Check if no changes
        if ($oldName === $newName && $oldKategoriId === $newKategoriId) {
            return redirect()->back()->with('info', 'Tidak ada perubahan data.');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $this->sarprasModel->set([
                                'nama' => $newName,
                                'kategori_id' => $newKategoriId
                           ])
                           ->where('nama', $oldName)
                           ->where('kategori_id', $oldKategoriId)
                           ->update();

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->with('error', 'Gagal mengubah data barang.');
        }

        log_activity('Sarpras', 'UPDATE', "Update grup '$oldName' -> '$newName' (Kategori ID: $newKategoriId)");
        return redirect()->to('/admin/sarpras')->with('success', "Data barang berhasil diperbarui.");
    }
}
