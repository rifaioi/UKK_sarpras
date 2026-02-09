<?php

namespace App\Models;

use CodeIgniter\Model;

class MaintenanceReminderModel extends Model
{
    protected $table = 'maintenance_reminders';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'sarpras_id',
        'due_date',
        'reminder_type',
        'is_read',
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = '';

    /**
     * Get unread reminders for dashboard
     */
    public function getUnreadReminders()
    {
        return $this->select('maintenance_reminders.*, 
                             sarpras.nama as nama_barang,
                             sarpras.kode')
                    ->join('sarpras', 'sarpras.id = maintenance_reminders.sarpras_id')
                    ->where('maintenance_reminders.is_read', 0)
                    ->orderBy('maintenance_reminders.due_date', 'ASC')
                    ->findAll();
    }

    /**
     * Mark reminder as read
     */
    public function markAsRead($id)
    {
        return $this->update($id, ['is_read' => 1]);
    }

    /**
     * Check if reminder already exists for specific asset and type
     */
    public function reminderExists($sarprasId, $dueDate, $reminderType)
    {
        return $this->where('sarpras_id', $sarprasId)
                    ->where('due_date', $dueDate)
                    ->where('reminder_type', $reminderType)
                    ->first() !== null;
    }

    /**
     * Generate reminders for upcoming maintenance
     * Called on login or periodically
     */
    public function generateReminders()
    {
        $maintenanceModel = new MaintenanceRecordModel();
        $db = \Config\Database::connect();

        // Get all maintenance records with future dates
        $records = $db->table('maintenance_records')
                     ->select('id, sarpras_id, next_maintenance_date')
                     ->where('next_maintenance_date IS NOT NULL')
                     ->get()
                     ->getResultArray();

        $generated = 0;
        $today = date('Y-m-d');

        foreach ($records as $record) {
            $dueDate = $record['next_maintenance_date'];
            $sarprasId = $record['sarpras_id'];
            $daysUntilDue = (strtotime($dueDate) - strtotime($today)) / (60 * 60 * 24);

            // H-7 Reminder
            if ($daysUntilDue <= 7 && $daysUntilDue > 3) {
                if (!$this->reminderExists($sarprasId, $dueDate, 'H-7')) {
                    $this->insert([
                        'sarpras_id' => $sarprasId,
                        'due_date' => $dueDate,
                        'reminder_type' => 'H-7',
                        'is_read' => 0,
                    ]);
                    $generated++;
                }
            }

            // H-3 Reminder
            if ($daysUntilDue <= 3 && $daysUntilDue > 0) {
                if (!$this->reminderExists($sarprasId, $dueDate, 'H-3')) {
                    $this->insert([
                        'sarpras_id' => $sarprasId,
                        'due_date' => $dueDate,
                        'reminder_type' => 'H-3',
                        'is_read' => 0,
                    ]);
                    $generated++;
                }
            }

            // H-0 Reminder (due today)
            if ($daysUntilDue == 0) {
                if (!$this->reminderExists($sarprasId, $dueDate, 'H-0')) {
                    $this->insert([
                        'sarpras_id' => $sarprasId,
                        'due_date' => $dueDate,
                        'reminder_type' => 'H-0',
                        'is_read' => 0,
                    ]);
                    $generated++;
                }
            }

            // Overdue Reminder
            if ($daysUntilDue < 0) {
                if (!$this->reminderExists($sarprasId, $dueDate, 'overdue')) {
                    $this->insert([
                        'sarpras_id' => $sarprasId,
                        'due_date' => $dueDate,
                        'reminder_type' => 'overdue',
                        'is_read' => 0,
                    ]);
                    $generated++;
                }
            }
        }

        return $generated;
    }
}
