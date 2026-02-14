<?php

namespace App\Controllers\Petugas;

use App\Controllers\BaseController;
use App\Models\PeminjamanModel;
use App\Models\SarprasModel;
use App\Models\InspectionChecklistItemModel;
use App\Models\InspectionModel;
use App\Models\InspectionResultModel;

class Inspections extends BaseController
{
    protected $peminjamanModel;
    protected $sarprasModel;
    protected $checklistItemModel;
    protected $inspectionModel;
    protected $inspectionResultModel;
    protected $pengembalianModel;

    public function __construct()
    {
        $this->peminjamanModel = new PeminjamanModel();
        $this->sarprasModel = new SarprasModel();
        $this->checklistItemModel = new InspectionChecklistItemModel();
        $this->inspectionModel = new InspectionModel();
        $this->inspectionResultModel = new InspectionResultModel();
        $this->pengembalianModel = new \App\Models\PengembalianModel();
    }

    public function create($peminjamanId)
    {
        $peminjaman = $this->peminjamanModel->select('peminjaman.*, users.nama_lengkap as peminjam, sarpras.nama as barang, sarpras.kode as kode_barang, sarpras.kategori_id')
            ->join('users', 'users.id = peminjaman.user_id')
            ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
            ->find($peminjamanId);

        if (!$peminjaman) {
            return redirect()->to('/petugas/peminjaman')->with('error', 'Data peminjaman tidak ditemukan.');
        }

        // Determine type from query string if not set (default keluar)
        $type = $this->request->getGet('type') ?? 'keluar';
        
        // Normalize type
        if ($type == 'post-return' || $type == 'returning') $type = 'kembali';
        if ($type == 'pre-borrow' || $type == 'borrowing') $type = 'keluar';

        // Get checklist items for this category
        $checklistItems = $this->checklistItemModel->where('kategori_id', $peminjaman['kategori_id'])->findAll();

        // If Kembali, fetch Inspeksi Keluar Data for Comparison
        $preBorrowResults = [];
        if ($type == 'kembali') {
            $preInspection = $this->inspectionModel->where('peminjaman_id', $peminjamanId)
                ->where('type', 'keluar')
                ->orderBy('created_at', 'DESC')
                ->first();
            
            if ($preInspection) {
                // Fetch Checklist Results
                $rawResults = $this->inspectionResultModel->where('inspection_id', $preInspection['id'])->findAll();
                foreach ($rawResults as $res) {
                    $preBorrowResults[$res['checklist_item_id']] = $res;
                }
                
                // Pass the pre-borrow inspection data itself (for photo)
                $data['preInspection'] = $preInspection;
            }
        }

        $role = session()->get('role_id') == 1 ? 'admin' : 'petugas';

        $data['title'] = 'Pemeriksaan & Checklist Barang';
        $data['peminjaman'] = $peminjaman;
        $data['checklistItems'] = $checklistItems;
        $data['type'] = $type;
        $data['preBorrowResults'] = $preBorrowResults;
        $data['role'] = $role;

        return view('petugas/inspections/form', $data);
    }

    public function store()
    {
        $peminjamanId = $this->request->getPost('peminjaman_id');
        $type = $this->request->getPost('type'); // 'keluar' or 'kembali'
        
        // Safety fallback for legacy/unexpected types
        if ($type == 'post-return' || $type == 'returning') $type = 'kembali';
        if ($type == 'pre-borrow' || $type == 'borrowing') $type = 'keluar';
        
        // Final fallback if still invalid (default to kembali if on return path)
        if (!in_array($type, ['keluar', 'kembali'])) {
            $type = 'kembali';
        }
        
        // Validation
        if (!$this->validate([
            'photo' => 'permit_empty|max_size[photo,10240]|is_image[photo]|mime_in[photo,image/jpg,image/jpeg,image/png]',
            'results' => 'permit_empty', // Allow empty results if no checklist items exist
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Handle File Upload
        $photo = $this->request->getFile('photo');
        $photoName = null;
        if ($photo && $photo->isValid() && !$photo->hasMoved()) {
            $photoName = $photo->getRandomName();
            $photo->move(ROOTPATH . 'public/uploads/inspections', $photoName);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        // 1. Create Inspection Record
        $inspectionData = [
            'peminjaman_id' => $peminjamanId,
            'inspector_id' => session()->get('id'),
            'type' => $type,
            'inspection_date' => date('Y-m-d H:i:s'),
            'photo_evidence' => $photoName ? 'uploads/inspections/' . $photoName : null,
            'notes' => $this->request->getPost('notes'),
        ];
        $inspectionId = $this->inspectionModel->insert($inspectionData);

        // 2. Save Checklist Results
        $results = $this->request->getPost('results') ?? []; // Array [item_id => status]
        $descriptions = $this->request->getPost('descriptions'); // Array [item_id => desc]

        foreach ($results as $itemId => $status) {
            $desc = trim($descriptions[$itemId] ?? '');
            
            // T1-KEMBALI-003-LVL2: Mandatory item-level description if status is not OK
            if ($type == 'kembali' && ($status == 'damaged' || $status == 'missing') && empty($desc)) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', "Keterangan detail untuk item ID $itemId wajib diisi karena ada masalah/kerusakan.");
            }

            $this->inspectionResultModel->save([
                'inspection_id' => $inspectionId,
                'checklist_item_id' => $itemId,
                'status' => $status,
                'description' => $desc
            ]);
        }

        // 3. Logic based on Type
        if ($type == 'keluar') {
            // Update Status to 'Dipinjam' (2)
            $this->peminjamanModel->update($peminjamanId, ['status_id' => 2]);
            
            // Sync Sarpras State (Ensure stock is 0 and status is dipinjam)
            $peminjaman = $this->peminjamanModel->find($peminjamanId);
            if ($peminjaman) {
                // T1-PINJAM-010: Officially decrease stock when items leave the storage
                $this->sarprasModel->skipValidation(true)->update($peminjaman['sarpras_id'], [
                    'stok' => 0,
                    'status' => 'dipinjam'
                ]);
            }
            
            log_activity('Inspeksi', 'Inspeksi Keluar', "Inspeksi barang keluar untuk peminjaman #$peminjamanId selesai.");
            $redirectUrl = '/petugas/peminjaman';
            $msg = 'Inspeksi keluar selesai. Barang siap diserahkan.';
        } else {
            // 159: Inspeksi Kembali Logic
            // Determine Overall Condition based on checklist results
            // T1-KEMBALI-REVISI: If only 1 item is damaged (and 0 missing), ignore it for overall status (keep Baik)
            $overallConditionId = 1; // Default Baik
            $damageDetails = [];
            $damagedCount = 0;
            $missingCount = 0;

            foreach ($results as $itemId => $status) {
                if ($status == 'damaged') {
                    $damagedCount++;
                    $damageDetails[] = "Item $itemId: Rusak";
                } elseif ($status == 'missing') {
                    $missingCount++;
                    $damageDetails[] = "Item $itemId: Hilang";
                }
            }

            // Logic: If > 1 damaged OR any missing -> Overall NOT Baik
            if ($missingCount > 0) {
                $overallConditionId = 3; // Rusak Berat / Hilang Component
            } elseif ($damagedCount > 1) {
                $overallConditionId = 2; // Rusak Ringan
            } else {
                $overallConditionId = 1; // Still Baik (even if 1 damaged)
            }
            
            // Check manual notes too
            $notes = trim($this->request->getPost('notes'));

            // T1-KEMBALI-003: Mandatory notes if ANY damage/missing found (even for lenient 1-damage)
            if (($damagedCount > 0 || $missingCount > 0) && empty($notes)) {
                $db->transRollback();
                return redirect()->back()->withInput()->with('error', 'Catatan Tambahan wajib diisi karena ada item yang terdeteksi Rusak atau Hilang.');
            }
            
            // Save to Pengembalian Table
            $this->pengembalianModel->save([
                'peminjaman_id' => $peminjamanId,
                'tgl_pengembalian' => date('Y-m-d'),
                'kondisi_id' => $overallConditionId,
                'deskripsi' => "Pemeriksaan: " . implode(", ", $damageDetails) . ". Notes: " . $notes,
                'foto' => $photoName ? 'uploads/inspections/' . $photoName : null,
                'is_restocked' => ($overallConditionId == 1 || $overallConditionId == 2) ? 1 : 0 // Auto restock if good or lightly damaged
            ]);

            // Update Peminjaman Status to Kembali (4)
            $this->peminjamanModel->skipValidation(true)->update($peminjamanId, ['status_id' => 4]);

            // Update Sarpras Logic
            $peminjaman = $this->peminjamanModel->find($peminjamanId);
            if ($peminjaman) {
                $sarprasStatus = 'tersedia';
                if ($overallConditionId == 3) { // Only Rusak Berat
                    $sarprasStatus = 'rusak';
                } elseif ($overallConditionId == 4) {
                    $sarprasStatus = 'hilang';
                }

                $this->sarprasModel->skipValidation(true)->update($peminjaman['sarpras_id'], [
                    'kondisi_id' => $overallConditionId,
                    'stok' => ($overallConditionId == 1 || $overallConditionId == 2 ? 1 : 0),
                    'status' => $sarprasStatus
                ]);
            }
            
            // Compare with Pre-Borrow Inspection (Optional Feature: Auto-Flag)
            // We can fetch the pre-borrow inspection and compare.
            // For now, the user just asked to "Compare" which we do visually by showing the form, 
            // but automating the flag is done by the logic above (if damaged now, flag it).
            
            $redirectUrl = '/petugas/pengembalian';
            $msg = 'Inspeksi kembali selesai. Barang telah diterima.';
            
            if ($overallConditionId != 1) {
                $msg .= ' (Terdeteksi Kerusakan)';
            }
        }



        $db->transComplete();

        if ($db->transStatus() === false) {
            $error = $db->error();
            log_message('error', 'Inspection Save Error: ' . json_encode($error));
            return redirect()->back()->with('error', 'Gagal menyimpan data inspeksi. Error: ' . $error['message']);
        }

        // Determine Redirect URL based on Role
        $rolePrefix = session()->get('role_id') == 1 ? 'admin' : 'petugas';
        
        // Fix the hardcoded redirectUrl from above blocks
        if ($redirectUrl == '/petugas/peminjaman') {
            $redirectUrl = "/$rolePrefix/peminjaman";
        } elseif ($redirectUrl == '/petugas/pengembalian') {
            $redirectUrl = "/$rolePrefix/pengembalian";
        }

        return redirect()->to($redirectUrl)->with('success', $msg);
    }
}
