<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsDeletedColumns extends Migration
{
    public function up()
    {
        // Add is_deleted to locations
        if (!$this->db->fieldExists('is_deleted', 'locations')) {
            $this->forge->addColumn('locations', [
                'is_deleted' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'after' => 'keterangan'
                ],
            ]);
        }

        // Add sometimes peminjaman already has deleted_at from CodeIgniter SoftDeletes trait, 
        // but we want to standardize on is_deleted = 1/0 for this manual implementation 
        // OR we can make use of deleted_at if we want to use the framework's Model SoftDeletes.
        // User requested "is_deleted" specifically in the context of the conversation pattern.
        // But wait, the previous inspection showed `peminjaman` has `deleted_at`.
        
        // Let's add is_deleted to be consistent with existing 'kategori_sarpras', 'sarpras', 'users', 'pengaduan'
        // which all use 'is_deleted'.
        
        if (!$this->db->fieldExists('is_deleted', 'peminjaman')) {
            $this->forge->addColumn('peminjaman', [
                'is_deleted' => [
                    'type' => 'TINYINT',
                    'constraint' => 1,
                    'default' => 0,
                    'after' => 'deleted_at' // Assuming deleted_at is at the end
                ],
            ]);
        }
        
        // Add to inspection_template_items if exists
        // Note: Check if table exists first as it might have been removed or renamed?
        // The file `database_rev_tier2.sql` previously showed `inspection_template_items`.
        if ($this->db->tableExists('inspection_template_items')) {
             if (!$this->db->fieldExists('is_deleted', 'inspection_template_items')) {
                $this->forge->addColumn('inspection_template_items', [
                    'is_deleted' => [
                        'type' => 'TINYINT',
                        'constraint' => 1,
                        'default' => 0,
                    ],
                ]);
            }
        }
    }

    public function down()
    {
        if ($this->db->fieldExists('is_deleted', 'locations')) {
            $this->forge->dropColumn('locations', 'is_deleted');
        }
        if ($this->db->fieldExists('is_deleted', 'peminjaman')) {
            $this->forge->dropColumn('peminjaman', 'is_deleted');
        }
        if ($this->db->tableExists('inspection_template_items') && $this->db->fieldExists('is_deleted', 'inspection_template_items')) {
            $this->forge->dropColumn('inspection_template_items', 'is_deleted');
        }
    }
}
