<?php

namespace App\Controllers\Member;

use App\Controllers\BaseController;
use App\Models\SarprasModel;
use App\Models\PeminjamanModel;
use App\Models\PengaduanModel;
use App\Models\ActivityLogModel;

class Dashboard extends BaseController
{
    protected $sarprasModel;
    protected $peminjamanModel;
    protected $pengaduanModel;

    public function __construct()
    {
        $this->sarprasModel = new SarprasModel();
        $this->peminjamanModel = new PeminjamanModel();
        $this->pengaduanModel = new PengaduanModel();
    }

    public function index()
    {
        $userId = session()->get('id');

        $activeLoans = $this->peminjamanModel->where('user_id', $userId)
                                             ->where('status_id', 2)
                                             ->countAllResults();
                                             
        $pendingLoans = $this->peminjamanModel->where('user_id', $userId)
                                              ->where('status_id', 1)
                                              ->countAllResults();
                                              
        $activeComplaints = $this->pengaduanModel->where('user_id', $userId)
                                                 ->where('status_id !=', 3)
                                                 ->countAllResults();

        $activityModel = new ActivityLogModel();
        $recentActivities = $activityModel->where('user_id', $userId)
                                         ->whereNotIn('aksi', ['Login', 'Logout'])
                                         ->orderBy('created_at', 'DESC')
                                         ->limit(5)
                                         ->findAll();

        $data = [
            'active_loans_count' => $activeLoans,
            'pending_loans_count' => $pendingLoans,
            'active_complaints_count' => $activeComplaints,
            'recent_activities' => $recentActivities
        ];
        return view('member/dashboard', $data);
    }

    public function borrow($id)
    {
        $item = $this->sarprasModel->find($id);
        $data = ['item' => $item];
        return view('member/borrow_form', $data);
    }

    public function store_borrow()
    {
        $rules = [
             'jumlah' => 'required|numeric|greater_than[0]',
             'tgl_pinjam' => 'required|valid_date',
             'tgl_kembali_rencana' => 'required|valid_date'
        ];
        
        if (!$this->validate($rules)) {
             return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Robust parsing: Handle slash (D/M/Y) and dash (Y-m-d) formats
        $parseDate = function($input) {
            if (empty($input)) return null;
            if (strpos($input, '/') !== false) {
                $dt = \DateTime::createFromFormat('d/m/Y', $input);
                if ($dt) return $dt->format('Y-m-d');
            }
            $ts = strtotime($input);
            return $ts ? date('Y-m-d', $ts) : null;
        };

        $tglPinjam = $parseDate($this->request->getVar('tgl_pinjam'));
        $tglKembali = $parseDate($this->request->getVar('tgl_kembali_rencana'));

        if (!$tglPinjam || !$tglKembali) {
            return redirect()->back()->withInput()->with('error', 'Format tanggal tidak valid (gunakan YYYY-MM-DD atau DD/MM/YYYY).');
        }

        if (strtotime($tglKembali) < strtotime($tglPinjam)) {
            return redirect()->back()->withInput()->with('error', 'Tanggal kembali (rencana) tidak boleh lebih dahulu/kecil dari tanggal pinjam.');
        }

        $sarprasId = $this->request->getVar('sarpras_id');
        $jumlah = (int) $this->request->getVar('jumlah');
        // Dates are already normalized above
        
        $item = $this->sarprasModel->find($sarprasId);
        if (!$item) {
             return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        // 2. Double Booking / Overlap Check (T1-PINJAM-008, 010)
        // Find all potential units of this type
        $allUnits = $this->sarprasModel->where('nama', $item['nama'])
                                       ->where('kategori_id', $item['kategori_id'])
                                       ->where('location_id', $item['location_id'])
                                       ->where('kondisi_id', 1)
                                       ->whereNotIn('status', ['rusak', 'hilang'])
                                       ->findAll();

        if (count($allUnits) < $jumlah) {
            return redirect()->back()->withInput()->with('error', 'Maaf, total unit yang layak tidak mencukupi permintaan Anda.');
        }

        // Check which units are available for the requested dates
        $availableUnits = [];
        $db = \Config\Database::connect();

        foreach ($allUnits as $unit) {
            $isBooked = $this->peminjamanModel->where('sarpras_id', $unit['id'])
                           ->whereIn('status_id', [1, 2])
                           ->groupStart()
                                ->where('tgl_pinjam <=', $tglKembali)
                                ->where('tgl_kembali_rencana >=', $tglPinjam)
                           ->groupEnd()
                           ->countAllResults();
            
            if ($isBooked == 0) {
                $availableUnits[] = $unit;
            }

            if (count($availableUnits) >= $jumlah) {
                break; // Found enough units
            }
        }

        if (count($availableUnits) < $jumlah) {
             return redirect()->back()->withInput()->with('error', 'Barang tidak tersedia pada tanggal tersebut. Silakan pilih tanggal lain atau kurangi jumlah barang.');
        }

        // Create peminjaman records (one per unit)
        $pjCode = $this->generatePJCode();
        foreach ($availableUnits as $index => $unit) {
            $this->peminjamanModel->save([
                'kode_peminjaman' => $jumlah > 1 ? $pjCode . '-' . ($index + 1) : $pjCode,
                'user_id' => session()->get('id'),
                'sarpras_id' => $unit['id'],
                'jumlah' => 1,
                'tgl_pinjam' => $tglPinjam,
                'tgl_kembali_rencana' => $tglKembali,
                'status_id' => 1 
            ]);
        }
        
        log_activity('Request Peminjaman', 'Meminta pinjam barang: ' . $item['nama'] . ' (' . $jumlah . ' unit)');

        return redirect()->to('/member/dashboard')->with('success', 'Permintaan peminjaman (' . $jumlah . ' unit) berhasil dikirim. Menunggu persetujuan.');
    }

    private function generatePJCode()
    {
        $date = date('Ymd');
        $prefix = "PJ-$date-";
        
        $lastPJ = $this->peminjamanModel->where('kode_peminjaman LIKE', "$prefix%")
                                        ->orderBy('id', 'DESC')
                                        ->first();
        
        $nextNum = 1;
        if ($lastPJ) {
            $parts = explode('-', $lastPJ['kode_peminjaman']);
            $lastSeq = end($parts);
            $nextNum = (int)$lastSeq + 1;
        }
        
        return $prefix . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }
    public function cancel($id)
    {
        $peminjaman = $this->peminjamanModel->find($id);

        if (!$peminjaman) {
            return redirect()->to('/member/dashboard')->with('error', 'Peminjaman tidak ditemukan.');
        }

        // Ensure user owns this record
        if ($peminjaman['user_id'] != session()->get('id')) {
            return redirect()->to('/member/dashboard')->with('error', 'Akses ditolak.');
        }

        // Only allow cancelling "Menunggu Persetujuan" (status 1)
        if ($peminjaman['status_id'] != 1) {
            return redirect()->to('/member/dashboard')->with('error', 'Tidak bisa membatalkan peminjaman yang sudah diproses.');
        }

        $this->peminjamanModel->delete($id);

        log_activity('Batal Peminjaman', "Membatalkan permintaan peminjaman id: $id");

        return redirect()->to('/member/dashboard')->with('success', 'Permintaan peminjaman dibatalkan.');
    }
}
