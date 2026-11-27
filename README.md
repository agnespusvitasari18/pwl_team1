<div align="center">

# 🎓 PKLoka
### Sistem Informasi Praktik Kerja Lapangan (PKL) Berbasis Web Menggunakan Laravel

[![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-16%2B-336791?style=for-the-badge&logo=postgresql&logoColor=white)](https://www.postgresql.org)
[![License](https://img.shields.io/badge/License-MIT-green.svg?style=for-the-badge)](LICENSE)

<br>

<p align="center">
  <b>PKLoka</b> adalah platform terpadu yang memfasilitasi proses penyelenggaraan Praktik Kerja Lapangan (PKL) bagi siswa SMK dengan menghubungkan tiga pihak: <b>Siswa</b>, <b>Mitra Industri (Perusahaan)</b>, dan <b>Pihak Sekolah (Admin / Guru)</b>.
</p>

</div>

---

## 👥 Tim Pengembang (Kelompok 1)

Proyek ini disusun untuk memenuhi tugas besar mata kuliah **Pemrograman Web Lanjut**:

* **Dosen Pengampu:**
  1. **M. Iqbal Parabi, S.Si., M.T.**
  2. **Muhammad Ikhsan, S.Kom., M.Cs.**

* **Anggota Kelompok:**
  | No | Nama | NPM | Peran Utama |
  |:--:|---|:--:|---|
  | 1 | **Agnes Puspita Sari** | 2417052022 | Ketua Tim, Arsitektur Sistem, Autentikasi & RBAC |
  | 2 | **M. Altaf Rabbani** | 2457052010 | Modul Admin Sekolah (Persetujuan Perusahaan & CRUD Siswa) |
  | 3 | **Salsabila Eka Putri** | 2417052015 | Modul Siswa (Katalog Lowongan, Pendaftaran & Tracking) |
  | 4 | **Michela Irena Hutama** | 2417052009 | Modul Perusahaan (Manajemen Lowongan & Seleksi Pelamar) |

**Jurusan Ilmu Komputer · Program Studi S1 Sistem Informasi**  
**Fakultas Matematika dan Ilmu Pengetahuan Alam · Universitas Lampung**  
**2026**

---

## 📌 Fitur Utama

Sistem menerapkan **Role-Based Access Control (RBAC)** dengan 3 tingkatan hak akses yang terisolasi ketat pada level middleware:

```
┌────────────────────────────────────────────────────────┐
│                       SISTEM PKLOKA                    │
├─────────────────┬───────────────────┬──────────────────┤
│ 👨‍🏫 Admin Sekolah│ 🏢 Mitra Industri  │ 👤 Siswa         │
├─────────────────┼───────────────────┼──────────────────┤
│ • Validasi Mitra│ • CRUD Lowongan   │ • Filter Jurusan │
│ • CRUD Siswa    │ • Review Pelamar  │ • Lamar Online   │
│ • Audit Lamaran │ • Terima/Tolak    │ • Tracking Status│
│ • Rekap PKL     │ • Profil Instansi │ • Profil Diri    │
└─────────────────┴───────────────────┴──────────────────┘
```

### 1. 👨‍🏫 Pihak Sekolah (Admin / Guru)
- **Verifikasi Mitra Industri (*Company Approval*):** Menyaring pendaftaran perusahaan untuk mencegah lowongan fiktif sebelum dapat membuka lowongan.
- **Manajemen Siswa (*CRUD Student*):** Mendaftarkan akun siswa baru, mengelola data NISN, sekolah, jurusan, serta memperbarui/menghapus akun.
- **Monitoring Agregat:** Memantau seluruh lowongan yang diterbitkan dan rekap status seluruh lamaran siswa secara terpusat.

### 2. 🏢 Mitra Industri (Perusahaan)
- **Registrasi & Profil Perusahaan:** Mendaftarkan institusi secara mandiri serta mengelola profil, alamat, dan narahubung resmi.
- **Manajemen Lowongan (*CRUD Internship Posts*):** Membuat, mengedit, atau menutup lowongan PKL dengan kriteria jurusan (*major*), kuota siswa, dan batas waktu (*deadline*).
- **Proses Seleksi Pelamar:** Memeriksa data siswa pelamar, memutuskan penerimaan (*accept*) atau penolakan (*reject*).

### 3. 👤 Siswa SMA/SMK (Student)
- **Katalog Lowongan Tertarget:** Menampilkan lowongan yang relevan dan sesuai dengan kompetensi keahlian/jurusan siswa.
- **Pengajuan Pendaftaran (*One-Click Apply*):** Melamar lowongan PKL secara daring dan aman dari pendaftaran ganda.
- **Pelacakan Status Real-Time:** Memantau perkembangan status lamaran (*Menunggu*, *Diterima*, atau *Ditolak*).
- **Proteksi Penempatan Eksklusif:** Ketika siswa diterima di salah satu lowongan, sistem secara otomatis mengunci status siswa dan menggugurkan lamaran lain yang masih *pending*.

---

## 🚀 Panduan Instalasi & Menjalankan Proyek

### 1. Prasyarat Lingkungan
- PHP `>= 8.2` (Rekomendasi PHP 8.3)
- Composer `>= 2.x`
- Node.js `>= 18.x` & npm
- PostgreSQL (versi 15+ / 16+) atau pgAdmin

### 2. Langkah Instalasi

```bash
# 1. Masuk ke direktori proyek
cd c:\laragon\www\pwl_team1

# 2. Pasang dependensi PHP dan Node.js
composer install
npm install

# 3. Siapkan berkas environment
cp .env.example .env
php artisan key:generate
```

### 3. Konfigurasi Database (.env)
Buka berkas `.env` dan sesuaikan koneksi database PostgreSQL:
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=pkloka
DB_USERNAME=postgres
DB_PASSWORD=<password_kamu>
```
> *Catatan: Buat database dengan nama `pkloka` di PostgreSQL (via pgAdmin / psql CLI) sebelum menjalankan migrasi.*

### 4. Migrasi, Seeder & Storage Link
Jalankan migrasi skema tabel beserta data demo Bandar Lampung dan hubungkan direktori penyimpanan publik:
```bash
php artisan migrate:fresh --seed
php artisan storage:link
```

### 5. Build Aset & Jalankan Server
```bash
# Build aset CSS & JS (Tailwind CSS v4 + Vite)
npm run build

# Jalankan server lokal
php artisan serve
```
Akses sistem melalui peramban: **`http://127.0.0.1:8000`**

---

## 🔑 Akun Uji Coba (Demo Accounts — SMKN 4 Bandar Lampung)

Siswa dapat masuk menggunakan **alamat email** maupun **NISN**. Kata sandi semua akun demo adalah `password`:

| Peran | Email / NISN | Kata Sandi | Keterangan |
|---|---|---|---|
| **Admin Sekolah** | `admin@pkloka.test` | `password` | Admin SMKN 4 Bandar Lampung (`/admin`) |
| **Perusahaan (Disetujui)** | `tech@solusi.test` | `password` | PT Solusi Teknologi Nusantara (Bandar Lampung) |
| **Perusahaan (Menunggu)** | `info@logistikniaga.test` | `password` | PT Logistik Niaga Lampung (untuk uji verifikasi admin) |
| **Perusahaan (Ditolak)** | `cs@berkahjaya.test` | `password` | CV Berkah Jaya Mandiri (memiliki alasan penolakan) |
| **Siswa (PPLG — Diterima)** | `ahmad@siswa.test` / `0061234501` | `password` | Ahmad Fauzan (sudah diterima PKL, terkunci) |
| **Siswa (DKV — Menunggu)** | `siti@siswa.test` / `0061234502` | `password` | Siti Nurhaliza (lamaran berstatus menunggu) |
| **Siswa (PPLG — Belum Melamar)** | `bima@siswa.test` / `0061234505` | `password` | Bima Sakti (siap dipakai untuk uji melamar) |

---

## 🧪 Pengujian Otomatis (Automated Testing)

Sistem dilengkapi suite pengujian otomatis untuk memvalidasi alur bisnis, hak akses, penyimpanan CV privat, impor CSV, reset sandi, dan integritas transaksi database:

```bash
php artisan test
```

Skenario uji utama pada `tests/Feature/PklokaSystemTest.php`:
- [x] Akses halaman publik, login (Email & NISN), lupa kata sandi, dan registrasi mitra.
- [x] Isolasi hak akses antar peran (RBAC) Admin, Perusahaan, Siswa, serta isolasi antar perusahaan & halaman error 403/404.
- [x] Registrasi perusahaan -> tolak dengan alasan (alasan terlihat perusahaan saat login) -> setujui -> login.
- [x] Validasi lamaran (jurusan, batas waktu, kuota, larangan dobel, format PDF) & penyimpanan CV di disk privat dengan otorisasi ketat.
- [x] Seleksi pelamar dengan transaksi DB & `lockForUpdate`: pengecekan kuota, auto-reject lamaran pending lain, dan penguncian status siswa.
- [x] Alur lupa & atur ulang kata sandi menggunakan email maupun NISN.
- [x] Fitur Admin: CRUD siswa, reset sandi siswa (kustom & otomatis), impor akun siswa via CSV, dan ekspor rekap penempatan siswa CSV.
- [x] Analitik dashboard nyata & *smoke test* seluruh halaman di ketiga peran bebas dari galat HTTP 500.

---

## 📂 Struktur Direktori Proyek

```text
pwl_team1/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/         # Controller wewenang pihak sekolah
│   │   │   ├── Company/       # Controller wewenang mitra industri
│   │   │   ├── Student/       # Controller wewenang siswa
│   │   │   ├── Auth/          # Controller login & registrasi perusahaan
│   │   │   └── DashboardController.php
│   │   └── Middleware/
│   │       └── EnsureRole.php # RBAC middleware penegak hak akses
│   └── Models/                # User, StudentProfile, CompanyProfile, InternshipPost, Application, Major
├── database/
│   ├── migrations/            # Berkas migrasi skema tabel
│   └── seeders/               # AdminSeeder, MajorSeeder, DemoDataSeeder
├── resources/
│   ├── css/app.css            # Tailwind CSS styling
│   └── views/                 # Blade views modular (admin/, company/, student/, auth/, layouts/)
├── routes/
│   └── web.php                # 38 route bisnis aplikasi terproteksi
└── tests/
    └── Feature/               # Automated feature tests
```

---

## 📖 Dokumentasi Lengkap Proyek

Penjelasan arsitektur mendalam, kamus data lengkap, relasi ERD, peta 41 rute, kontrak transisi status, matriks pembagian tugas tim, hingga audit implementasi tersedia pada berkas:

👉 **[Lihat Dokumentasi Lengkap Project (dokumentasi project.md)](dokumentasi%20project.md)**

---

<div align="center">
  <sub>Dikembangkan oleh Kelompok 1 · Pemrograman Web Lanjut · Universitas Lampung 2026</sub>
</div>
