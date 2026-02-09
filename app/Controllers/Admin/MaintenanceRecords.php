<?php

namespace App\Controllers\Admin;

use App\Controllers\Petugas\MaintenanceRecords as PetugasMaintenanceRecords;
use App\Models\PengembalianModel;
use App\Models\SarprasModel;

class MaintenanceRecords extends PetugasMaintenanceRecords
{
    /**
     * Admin-specific: Priority Dashboard with Predictive Analytics
     */
    public function priorityDashboard()
    {
        $pengembalianModel = new PengembalianModel();
        $sarprasModel = new SarprasModel();

        // Get breakdown frequency per asset
        $breakdownStats = $pengembalianModel->select('
            peminjaman.sarpras_id,
            sarpras.nama as nama_barang,
            sarpras.kode,
            COUNT(*) as breakdown_count,
            SUM(CASE WHEN pengembalian.kondisi_id != 1 THEN 1 ELSE 0 END) as damage_count
        ')
        ->join('peminjaman', 'peminjaman.id = pengembalian.peminjaman_id')
        ->join('sarpras', 'sarpras.id = peminjaman.sarpras_id')
        ->where('sarpras.deleted_at', null)
        ->groupBy('peminjaman.sarpras_id')
        ->having('damage_count >', 0)
        ->findAll();

        // Calculate priority scores
        $priorities = [];
        foreach ($breakdownStats as $stat) {
            $sarprasId = $stat['sarpras_id'];
            
            // Get last maintenance date
            $lastMaintenance = $this->recordModel->getLatestByAsset($sarprasId);
            $daysSinceLastMaintenance = 0;
            
            if ($lastMaintenance) {
                $daysSinceLastMaintenance = (strtotime(date('Y-m-d')) - strtotime($lastMaintenance['maintenance_date'])) / (60 * 60 * 24);
            } else {
                $daysSinceLastMaintenance = 365; // If never maintained, high priority
            }

            // Priority Score = (Damage Count * 2) + (Days Since Last Maintenance / 30)
            $priorityScore = ($stat['damage_count'] * 2) + ($daysSinceLastMaintenance / 30);

            $priorities[] = [
                'sarpras_id' => $sarprasId,
                'nama_barang' => $stat['nama_barang'],
                'kode' => $stat['kode'],
                'breakdown_count' => $stat['breakdown_count'],
                'damage_count' => $stat['damage_count'],
                'days_since_maintenance' => round($daysSinceLastMaintenance),
                'priority_score' => round($priorityScore, 2),
                'last_maintenance_date' => $lastMaintenance ? $lastMaintenance['maintenance_date'] : 'Belum pernah',
            ];
        }

        // Sort by priority score descending
        usort($priorities, function($a, $b) {
            return $b['priority_score'] <=> $a['priority_score'];
        });

        $data = [
            'title' => 'Priority Maintenance Dashboard',
            'priorities' => $priorities,
        ];

        return view('admin/maintenance/priority_dashboard', $data);
    }
}
