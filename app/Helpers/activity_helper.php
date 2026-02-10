<?php

use App\Models\ActivityLogModel;

if (!function_exists('log_activity')) {
    function log_activity($aksi, $deskripsi = null, $metadata = null)
    {
        try {
            $logModel = new \App\Models\ActivityLogModel();
            $request = service('request');
            
            $user_id = session()->get('id');
            $ip_address = $request->getIPAddress();

            $logModel->save([
                'user_id' => $user_id, // Can be null for guest activities like failed login
                'aksi' => $aksi,
                'deskripsi' => $deskripsi,
                'ip_address' => $ip_address,
                'metadata' => $metadata
            ]);
        } catch (\Exception $e) {
            // Silently fail logging to not disrupt user flow
            log_message('error', 'Activity Log Failed: ' . $e->getMessage());
        }
    }
}
