# Portal CSIRT & Sistem Pengaduan Insiden Siber (Diskominfo)

![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=flat-square&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-%5E8.2-777BB4?style=flat-square&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=flat-square&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

Aplikasi web **CSIRT (Computer Security Incident Response Team) & Helpdesk Ticketing** berbasis **Laravel 12** yang dirancang untuk memfasilitasi pelaporan insiden keamanan siber, pelacakan status penanganan tiket secara *real-time*, publikasi berita keamanan informasi, serta agenda kegiatan CSIRT.

---

## ✨ Fitur Utama

### 1. Modul Publik (Guest)
- **Beranda / Landing Page CSIRT**: Menampilkan ringkasan layanan, statistik jumlah laporan masuk & terselesaikan, berita terbaru, dan informasi kontak.
- **Kirim Aduan Siber (Buat Tiket)**:
  - Formulir pelaporan insiden berdasarkan **Kecamatan**, **Layanan**, dan **Kategori Layanan** secara dinamis.
  - Validasi keamanan lampiran berlapis (*magic-bytes check* untuk PDF & JPG/JPEG, pemblokiran ekstensi berbahaya, serta penyimpanan di *private disk*).
- **Pelacakan Tiket (*Ticket Tracking*)**:
  - Setiap laporan mendapatkan kode unik (`TKT...`) untuk memantau status tiket (*Pending*, *Diterima/Approved*, *Selesai/Completed*, atau *Ditolak/Rejected*) beserta catatan penyelesaiannya.
- **Portal Berita & Agenda**: Publikasi artikel edukasi keamanan siber dan jadwal kegiatan/agenda CSIRT.

### 2. Modul Administrator
- **Dashboard Analitik & SLA**:
  - Statistik tiket *real-time*, tingkat kepatuhan SLA (*SLA Compliance*), grafik tren tiket harian/bulanan, serta analitik durasi penanganan.
- **Manajemen Siklus Hidup Tiket**:
  - Persetujuan (*Accept*), penolakan (*Reject* dengan alasan), dan penyelesaian (*Complete* dengan catatan resolusi).
  - **Work Session Timer**: Pencatatan waktu kerja nyata (*Start*, *Pause*, *Resume*, *Complete*) per tiket lengkap dengan tampilan kalender aktivitas.
  - **Notifikasi Email Otomatis** saat status tiket berubah & **Riwayat Aktivitas (*Activity Log*)**.
  - **Ekspor Laporan Tiket** ke format **PDF** (`barryvdh/laravel-dompdf`) maupun tampilan tabel cetak/ekspor.
- **Manajemen Master Data**:
  - CRUD Master Layanan & Kategori Layanan.
  - CRUD Berita & Kategori Berita.
  - CRUD Agenda/Events & Data Kecamatan.
  - Manajemen Akun Administrator & Profil Admin.

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: PHP ^8.2, Laravel Framework 12.x
- **Database**: MySQL / MariaDB
- **Frontend**: Blade Templates, Bootstrap 5, Vanilla JavaScript, Chart.js / FullCalendar
- **PDF Generator**: `barryvdh/laravel-dompdf` (^3.1)

---

## 📋 Prasyarat Sistem (Requirements)

Pastikan perangkat Anda telah terinstal:
- **PHP** >= 8.2 (dengan ekstensi `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `gd`/`dom`)
- **Composer** >= 2.x
- **MySQL / MariaDB** (dapat menggunakan **XAMPP** atau **Laragon**)
- **Git**

---

## 🚀 Cara Instalasi

Ikuti langkah-langkah berikut untuk memasang proyek di komputer lokal Anda:

### 1. Clone Repository
```bash
git clone https://github.com/Whanss/Diskominfo-CISRT.git
cd Diskominfo-CISRT
```

### 2. Instal Dependensi PHP (Composer)
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` menjadi `.env`, lalu buat *application key* baru:
```bash
cp .env.example .env
php artisan key:generate
```
> **Catatan Windows (PowerShell/CMD):** Jika perintah `cp` tidak tersedia, gunakan `copy .env.example .env`.

Buka file `.env` dan sesuaikan pengaturan database MySQL Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=t_csirt
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Buat Database & Jalankan Migrasi + Seeder
Pastikan service **MySQL** (XAMPP/Laragon) sudah menyala dan buat database kosong bernama `t_csirt` di phpMyAdmin/MySQL, kemudian jalankan:
```bash
php artisan migrate --seed
```

### 5. Buat Symbolic Link Storage
Agar gambar berita dan agenda dapat diakses oleh publik, jalankan:
```bash
php artisan storage:link
```

---

## ▶️ Cara Menjalankan Aplikasi

Jalankan server pengembangan lokal Laravel dengan perintah:
```bash
php artisan serve
```

Setelah server aktif, buka browser dan akses:
- **Halaman Publik (Guest)**: [http://127.0.0.1:8000](http://127.0.0.1:8000)
- **Halaman Login Admin**: [http://127.0.0.1:8000/admin/login](http://127.0.0.1:8000/admin/login)

### 🔐 Akun Default Admin (Hasil Seeder)
Jika Anda menjalankan `php artisan migrate --seed`, gunakan kredensial berikut untuk masuk ke dashboard Admin:
- **Email**: `admin@gmail.com`
- **Password**: `admin123`

---

## 📄 Lisensi

Proyek ini dilisensikan di bawah **MIT License** — lihat file [LICENSE](LICENSE) untuk detail selengkapnya.
