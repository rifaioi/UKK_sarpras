<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AnalyticsModel;

class Analytics extends BaseController
{
    protected $analyticsModel;

    public function __construct()
    {
        $this->analyticsModel = new AnalyticsModel();
    }

    public function index()
    {
        $allLifecycleData = $this->prepareLifecycleData();
        
        // Manual Pagination
        $perPage = 10;
        $currentPage = $this->request->getVar('page_lifecycle') ? (int) $this->request->getVar('page_lifecycle') : 1;
        $total = count($allLifecycleData);
        
        $pager = \Config\Services::pager();
        
        $data = [
            'title' => 'Advanced Analytics & Asset Lifecycle',
            'top_damaged' => $this->analyticsModel->getTopDamagedAssets(),
            'damage_by_category' => $this->analyticsModel->getDamageByCategory(),
            'damage_trend' => $this->analyticsModel->getDamageTrend(),
            'damage_by_user' => $this->analyticsModel->getDamageByUser(),
            'lifecycle_data' => array_slice($allLifecycleData, ($currentPage - 1) * $perPage, $perPage),
            'pager' => $pager->makeLinks($currentPage, $perPage, $total, 'glass_pagination', 0, 'lifecycle'),
        ];

        return view('admin/analytics/index', $data);
    }

    /**
     * Prepare lifecycle data with recommendations
     */
    private function prepareLifecycleData()
    {
        $raw = $this->analyticsModel->getAssetLifecycleData();
        $costs = [];
        foreach ($this->analyticsModel->getMaintenanceCosts() as $c) {
            $costs[$c['sarpras_id']] = $c['total_cost'];
        }

        $processed = [];
        $today = new \DateTime();

        foreach ($raw as $item) {
            $purchaseDate = $item['tgl_pengadaan'] ? new \DateTime($item['tgl_pengadaan']) : null;
            $ageMonths = 0;
            if ($purchaseDate) {
                $diff = $today->diff($purchaseDate);
                $ageMonths = ($diff->y * 12) + $diff->m;
            }

            $expectedLifeMonths = ($item['expected_lifespan'] ?? 5) * 12;
            $usagePercentage = $expectedLifeMonths > 0 ? ($ageMonths / $expectedLifeMonths) * 100 : 0;
            
            $maintenanceCost = $costs[$item['id']] ?? 0;
            
            // Logic for Recommendation
            $recommendation = 'MANTAP';
            $badgeColor = 'success';

            if ($usagePercentage >= 100) {
                $recommendation = 'REKOMENDASI GANTI (Overlife)';
                $badgeColor = 'danger';
            } elseif ($maintenanceCost > ($item['harga_beli'] * 0.5) && $item['harga_beli'] > 0) {
                $recommendation = 'EVALUASI (Biaya Perawatan Tinggi)';
                $badgeColor = 'warning';
            } elseif ($usagePercentage > 80) {
                $recommendation = 'PANTAU (Mendekati Akhir Umur)';
                $badgeColor = 'warning';
            }

            $processed[] = [
                'nama' => $item['nama'],
                'kode' => $item['kode'],
                'category' => $item['category_name'],
                'age_months' => $ageMonths,
                'lifespan_months' => $expectedLifeMonths,
                'usage_percent' => round($usagePercentage, 1),
                'cost' => $maintenanceCost,
                'recommendation' => $recommendation,
                'badge_color' => $badgeColor
            ];
        }

        return $processed;
    }
}
