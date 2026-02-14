<?php

use App\Models\ActivityLogModel;

if (!function_exists('log_activity')) {
    function log_activity($module, $aksi, $deskripsi = null, $metadata = null)
    {
        try {
            $logModel = new \App\Models\ActivityLogModel();
            $request = service('request');
            
            $user_id = session()->get('id');
            $ip_address = $request->getIPAddress();
            $agent = $request->getUserAgent();
            $user_agent = $agent->getAgentString();

            $logModel->save([
                'user_id' => $user_id, // Can be null for guest activities like failed login
                'module' => $module,
                'aksi' => $aksi,
                'deskripsi' => $deskripsi,
                'ip_address' => $ip_address,
                'user_agent' => $user_agent,
                'metadata' => $metadata
            ]);
        } catch (\Exception $e) {
            // Silently fail logging to not disrupt user flow
            log_message('error', 'Activity Log Failed: ' . $e->getMessage());
        }
    }
}
