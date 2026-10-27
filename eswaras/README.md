# Sistem Informasi Kedai Es Waras (Tugas 6)

## Struktur Direktori Proyek
- `assets/` : Menyimpan aset gambar menu dan logo kedai.
- `config/` : Berisi file konfigurasi koneksi basis data (`koneksi.php`).
- `database/` : Menyimpan berkas inisialisasi basis data SQL (`db_eswaras.sql`).
- `index.php` : Halaman utama pemesanan pelanggan.
- `admin.php` : Dashboard pengelola menu dan riwayat transaksi.
- `README.md` : Dokumentasi penjelasan proyek.

## Cara Instalasi & Menjalankan Lokal (XAMPP)
1. Letakkan folder `eswaras` ke dalam direktori `htdocs` XAMPP.
2. Buat database baru bernama `db_eswaras` di phpMyAdmin, lalu impor file dari folder `database/db_eswaras.sql`.
3. Akses melalui browser di alamat: `http://localhost/eswaras/`