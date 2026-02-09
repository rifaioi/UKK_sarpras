<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixInspectionColumns extends Migration
{
    public function up()
    {
        // 1. Fix inspections table
        $fields = [
            'evidence_photo' => [
                'name' => 'photo_evidence',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('inspections', $fields);

        // 2. Fix inspection_results table
        // Change is_ok to status ENUM
        // Rename keterangan to description
        $fieldsResults = [
            'is_ok' => [
                'name' => 'status',
                'type' => 'ENUM',
                'constraint' => ['ok', 'damaged', 'missing', 'n/a'],
                'default' => 'ok',
            ],
            'keterangan' => [
                'name' => 'description',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('inspection_results', $fieldsResults);
    }

    public function down()
    {
        // Revert inspections
        $fields = [
            'photo_evidence' => [
                'name' => 'evidence_photo',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('inspections', $fields);

        // Revert inspection_results
        // We lose the detailed status info here converting back to boolean
        $fieldsResults = [
            'status' => [
                'name' => 'is_ok',
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1,
            ],
            'description' => [
                'name' => 'keterangan',
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
        ];
        $this->forge->modifyColumn('inspection_results', $fieldsResults);
    }
}
