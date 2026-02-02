<?php

use App\Models\ActivityLogModel;

if (!function_exists('log_activity')) {
    function log_activity($aksi, $deskripsi = null)
    {
        try {
            $logModel = new \App\Models\ActivityLogModel();
            $user_id = session()->get('id');

            if ($user_id) {
                $logModel->save([
                    'user_id' => $user_id,
                    'aksi' => $aksi,
                    'deskripsi' => $deskripsi
                ]);
            }
        } catch (\Exception $e) {
            // Silently fail logging to not disrupt user flow
            log_message('error', 'Activity Log Failed: ' . $e->getMessage());
        }
    }
}
