# Developer Guide - Aplikasi Sarpras UKK 2026

Dokumentasi teknis ini ditujukan bagi pengembang yang ingin melakukan kustomisasi atau pemeliharaan sistem Sarpras.

## 🛠 Tech Stack
- **Framework**: CodeIgniter 4.4.x
- **PHP**: 8.1+
- **Database**: MySQL 8.0 / MariaDB 10.4+
- **Frontend**: Bootstrap 5.3, Chart.js, Bootstrap Icons

## 📂 Struktur Direktori Penting
- `app/Controllers`: Logika aplikasi, dibagi berdasarkan Role (Admin, Petugas, Member).
- `app/Models`: Interaksi database.
- `app/Views`: Template UI Bootstrap 5.
- `public/uploads`: Lokasi penyimpanan foto bukti pengaduan dan gambar profil.

## 🗄️ Skema Database (Ringkasan)
Aplikasi menggunakan beberapa tabel utama:
1. `users`: Menyimpan data pengguna dan `role_id`.
2. `sarpras`: Tabel master data barang/aset.
3. `peminjaman`: Transaksi peminjaman barang.
4. `pengembalian`: Transaksi pengembalian dan pencatatan kondisi akhir.
5. `pengaduan`: Laporan kerusakan dari siswa.
6. `activity_log`: Audit trail aktivitas user di sistem.

## 🚀 Instalasi & Development
1. Clone repositori.
2. Konfigurasi `.env` (Database & App Base URL).
3. Jalankan `php spark migrate` untuk struktur tabel.
4. Jalankan `php spark db:seed MainSeeder` untuk data awal/default.
5. Gunakan `php spark serve` untuk menjalankan server dev lokal.

## 🛡️ Keamanan & Kualitas Kode
- **CSRF Protection**: Selalu aktif untuk semua form POST.
- **Validation**: Menggunakan library validasi CI4 di sisi server.
- **Role Middleware**: Proteksi route berdasarkan `role_id` (Filter).
- **Escaping**: Gunakan function `esc()` pada view untuk mencegah XSS.
