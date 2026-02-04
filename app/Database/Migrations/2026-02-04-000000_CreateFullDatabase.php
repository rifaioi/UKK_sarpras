<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateFullDatabase extends Migration
{
    public function up()
    {
        // 1. Roles
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_role' => ['type' => 'VARCHAR', 'constraint' => '100'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('roles');

        // 2. Locations
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_lokasi' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'keterangan' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('locations');

        // 3. Tipe Kategori
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_tipe' => ['type' => 'VARCHAR', 'constraint' => '100'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tipe_kategori');

        // 4. Kategori Sarpras
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'tipe_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'is_deleted' => ['type' => 'TINYINT', 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tipe_id', 'tipe_kategori', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kategori_sarpras');

        // 5. Kondisi Alat
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_kondisi' => ['type' => 'VARCHAR', 'constraint' => '100'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('kondisi_alat');

        // 6. Sarpras
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'parent_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'kode' => ['type' => 'VARCHAR', 'constraint' => '50'], // Not unique index anymore
            'nama' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'kategori_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'location_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'stok' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'kondisi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'is_deleted' => ['type' => 'TINYINT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('parent_id', 'sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kategori_id', 'kategori_sarpras', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('location_id', 'locations', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('kondisi_id', 'kondisi_alat', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('sarpras');

        // 7. Users
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username' => ['type' => 'VARCHAR', 'constraint' => '100', 'unique' => true],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => '255'],
            'nama_lengkap' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'role_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'is_deleted' => ['type' => 'TINYINT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('role_id', 'roles', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('users');

        // 8. Status Peminjaman
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_status' => ['type' => 'VARCHAR', 'constraint' => '100'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('status_peminjaman');

        // 9. Peminjaman
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sarpras_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jumlah' => ['type' => 'INT', 'constraint' => 11],
            'tgl_pinjam' => ['type' => 'DATE'],
            'tgl_kembali_rencana' => ['type' => 'DATE'],
            'tujuan' => ['type' => 'TEXT'],
            'status_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'rejection_reason' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sarpras_id', 'sarpras', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('status_id', 'status_peminjaman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('peminjaman');

        // 10. Status Pengaduan
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_status' => ['type' => 'VARCHAR', 'constraint' => '100'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('status_pengaduan');

        // 11. Pengaduan
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'sarpras_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'judul' => ['type' => 'VARCHAR', 'constraint' => '150'],
            'deskripsi' => ['type' => 'TEXT'],
            'lokasi' => ['type' => 'VARCHAR', 'constraint' => '100'],
            'bukti_foto' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'status_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'catatan' => ['type' => 'TEXT', 'null' => true],
            'is_deleted' => ['type' => 'TINYINT', 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
            'deleted_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('sarpras_id', 'sarpras', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('status_id', 'status_pengaduan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengaduan');

        // 12. Pengembalian
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'peminjaman_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'tgl_pengembalian' => ['type' => 'DATE'],
            'kondisi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'foto' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'is_restocked' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('peminjaman_id', 'peminjaman', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kondisi_id', 'kondisi_alat', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pengembalian');

        // 13. Activity Log
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'aksi' => ['type' => 'VARCHAR', 'constraint' => '50'],
            'deskripsi' => ['type' => 'TEXT', 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('activity_log');
    }

    public function down()
    {
        // Dropping in reverse order of dependencies
        $this->forge->dropTable('activity_log');
        $this->forge->dropTable('pengembalian');
        $this->forge->dropTable('pengaduan');
        $this->forge->dropTable('status_pengaduan');
        $this->forge->dropTable('peminjaman');
        $this->forge->dropTable('status_peminjaman');
        $this->forge->dropTable('users');
        $this->forge->dropTable('sarpras');
        $this->forge->dropTable('kondisi_alat');
        $this->forge->dropTable('kategori_sarpras');
        $this->forge->dropTable('tipe_kategori');
        $this->forge->dropTable('locations');
        $this->forge->dropTable('roles');
    }
}
