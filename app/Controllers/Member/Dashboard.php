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
                                             ->where('status_id', 2) // Disetujui
                                             ->countAllResults();
                                             
        $pendingLoans = $this->peminjamanModel->where('user_id', $userId)
                                              ->where('status_id', 1) // Menunggu
                                              ->countAllResults();
                                              
        $activeComplaints = $this->pengaduanModel->where('user_id', $userId)
                                                 ->where('status_id !=', 3) // Not Selesai
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

        $sarprasId = $this->request->getVar('sarpras_id');
        $jumlah = $this->request->getVar('jumlah');
        $tglPinjam = $this->request->getVar('tgl_pinjam');
        $tglKembali = $this->request->getVar('tgl_kembali_rencana');
        
        $item = $this->sarprasModel->find($sarprasId);
        if (!$item) {
             return redirect()->back()->with('error', 'Barang tidak ditemukan.');
        }

        // 2. Double Booking / Overlap Check
        // Check if there are any active loans (Status 1 or 2) for this item that overlap with requested dates
        // Overlap Formula: (StartA <= EndB) and (EndA >= StartB)
        $db = \Config\Database::connect();
        $overlapping = $db->table('peminjaman')
                          ->selectSum('jumlah')
                          ->where('sarpras_id', $sarprasId)
                          ->whereIn('status_id', [1, 2]) // Menunggu or Disetujui
                          ->groupStart()
                                ->where('tgl_pinjam <=', $tglKembali)
                                ->where('tgl_kembali_rencana >=', $tglPinjam)
                          ->groupEnd()
                          ->get()->getRow();
        
        $bookedQty = $overlapping ? $overlapping->jumlah : 0;
        
        // We assume 'stok' in Sarpras table is the TOTAL capacity if we are doing calendar-based booking.
        // HOWEVER, in this system, 'stok' is decremented on approval.
        // This hybrid approach is tricky. 
        // IF we rely on 'stok' decrement, we only care about "Available NOW".
        // IF we want "Future Booking", we must allow booking even if 'stok' (current) is 0, AS LONG AS it will be returned by then.
        // But that requires robust inventory management (Total vs Available).
        // 
        // SIMPLIFIED LOGIC requested by User: "Prevent double booking".
        // "Jika alat X sudah dipinjam... pengguna lain tidak bisa..."
        // This implies we should check against the "Booked Qty" for that period.
        // Let's assume 'stok' in DB is roughly "Total Asset Count" that limits concurrency.
        // If (BookedQty + RequestQty > TotalStock), then REJECT.
        
        // To make this work safely without changing DB schema (adding total_qty), 
        // let's assume the 'stok' value we read from DB *plus* any active loans *currently out* equals Total Capacity.
        // OR, just assume 'stok' is the limit for *simultaneous* usage.
        
        // Let's use the standard "Capacity Check":
        // We need to know the Total Capacity of the item.
        // Since 'stok' decreases, we might ideally need a fixed 'initial_stock'. 
        // Without it, we might block valid requests.
        // 
        // DECISION: For this task, we will check if the REQUESTED dates overlap with ANY existing loan.
        // If there is ANY overlap that consumes the stock, we block.
        // How to define "Consumes stock"?
        // Detailed: Calculate max usage on any single day within the requested range.
        
        // Simple Version:
        // If (BookedQty + $jumlah > $item['stok'] + $some_correction), Block.
        // Since we don't have 'total_stock', let's rely on the current 'stok' being the "Available for new bookings".
        // But 'stok' is current available. 
        // If I book for next month, 'stok' (today) is high. 
        // But maybe next month it's fully booked.
        // So checking 'stok' (today) is insufficient. 
        // And checking 'bookedQty' (next month) is correct.
        // But what is the Max Capacity? 
        // Let's assume: Real Capacity = (Current Stok + Sum of All Currently Active Loans).
        // Active Loans = Status 2 (Dipinjam). Status 1 (Menunggu) hasn't reduced stock yet?
        // Usually, stock is reduced when Status becomes 2.
        
        // Recovery of Total Capacity:
        $activeNow = $db->table('peminjaman')
                        ->selectSum('jumlah')
                        ->where('sarpras_id', $sarprasId)
                        ->where('status_id', 2) // Currently out
                        ->get()->getRow();
        $totalCapacity = $item['stok'] + ($activeNow ? $activeNow->jumlah : 0);
        
        if (($bookedQty + $jumlah) > $totalCapacity) {
             return redirect()->back()->with('error', 'Barang tidak tersedia pada tanggal tersebut. Sudah dipesan: ' . $bookedQty . ' unit.');
        }

        // Create peminjaman (Status 1: Menunggu Persetujuan)
        $this->peminjamanModel->save([
            'user_id' => session()->get('id'),
            'sarpras_id' => $sarprasId,
            'jumlah' => $jumlah,
            'tgl_pinjam' => $tglPinjam,
            'tgl_kembali_rencana' => $tglKembali,
            'status_id' => 1 
        ]);
        
        log_activity('Request Peminjaman', 'Meminta pinjam barang: ' . $item['nama']);

        return redirect()->to('/member/dashboard')->with('success', 'Permintaan peminjaman berhasil dikirim. Menunggu persetujuan.');
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

        return redirect()->to('/member/dashboard')->with('success', 'Permintaan peminjaman dibatalkan.');
    }
}
