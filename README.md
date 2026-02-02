# UKK Sarpras - Sistem Informasi Sarana dan Prasarana

Sistem manajemen inventaris, peminjaman, dan pengaduan sarana prasarana sekolah yang dirancang untuk kemudahan penggunaan oleh Admin, Petugas, dan Siswa (Member).

## 🚀 Fitur Utama

- **Dashboard Informatif**: Statistik real-time dan shortcut aktivitas untuk semua level user.
- **Manajemen Inventaris**: Pengelolaan data barang, lokasi, dan kategori dengan kode inventaris otomatis.
- **Sistem Peminjaman Terintegrasi**: 
    - Booking barang dengan validasi stok otomatis.
    - **Anti Double-Booking**: Mencegah bentrok jadwal peminjaman di tanggal yang sama.
    - Manajemen tenggat waktu dan pengembalian barang.
- **Laporan Pengaduan Kerusakan**:
    - Member dapat melaporkan kerusakan barang dengan bukti foto.
    - Admin/Petugas dapat memberikan catatan tindak lanjut dan update status progress.
- **Laporan & Log**:
    - Rekapitulasi peminjaman dan pengaduan dalam format tabel.
    - Grafik tren peminjaman bulanan.
    - Log aktivitas sistem untuk audit keamanan.
- **UI Modern & User Friendly**:
    - Sidebar fixed (diam) untuk navigasi cepat.
    - Desain simpel dan bersih (clean design).
    - Responsif untuk akses melalui perangkat mobile.

## 🛠️ Tech Stack

- **Framework**: [CodeIgniter 4](https://codeigniter.com)
- **Bahasa**: PHP 8.1+
- **Database**: MySQL / MariaDB
- **Frontend**: Bootstrap 5, Bootstrap Icons, Chart.js
- **Assets**: Custom CSS dengan desain modern.

## 📖 Dokumentasi
Untuk tutorial penggunaan dan informasi teknis, silakan merujuk pada:
- **[Panduan Pengguna (User Manual)](file:///d:/laragon/www/UKK_sarpras/docs/USER_MANUAL.md)**
- **[Panduan Pengembang (Developer Guide)](file:///d:/laragon/www/UKK_sarpras/docs/DEVELOPER_GUIDE.md)**

## ⚙️ Instalasi

1. **Clone repositori** atau ekstrak file project ke direktori web server (misal: Laragon/XAMPP).
2. **Setup Environment**:
    - Salin file `env` menjadi `.env`.
    - Atur `database.default.database`, `username`, dan `password` sesuai database Anda.
    - Atur `app.baseURL` ke URL lokal Anda (misal: `http://localhost/UKK_sarpras/public`).
3. **Migrasi Database**:
    - Jalankan perintah PHP Spark di terminal:
      ```bash
      php spark migrate
      php spark db:seed MainSeeder
      ```
4. **Jalankan Aplikasi**:
    - Buka browser dan akses melalui `localhost`.

## 👥 Hak Akses Default (Seeder)

| Role | Username | Password |
| :--- | :--- | :--- |
| **Admin** | admin | 12345678 |
| **Petugas** | petugas | 12345678 |
| **Member (Siswa)** | member | 12345678 |

