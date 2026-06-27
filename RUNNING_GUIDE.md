# 📖 MANUAL RUNNING GUIDE - Smart Queue System

**Jika Anda lebih suka setup manual step-by-step, ikuti panduan ini.**

---

## 🎯 Ringkasan Cepat

```
1. Install XAMPP
2. Copy folder ke C:\xampp\htdocs\
3. Buat database & import SQL
4. Akses aplikasi di browser
5. Login & test fitur
```

---

## LANGKAH 1: Install XAMPP (Jika Belum)

### Windows:

1. **Download XAMPP**
   - Buka: https://www.apachefriends.org/
   - Download: **XAMPP Windows**
   - Pilih versi: **PHP 8.0+** (atau 7.4)

2. **Install**
   - Jalankan installer: `xampp-windows-x64-8.0.xx-installer.exe`
   - Klik "Next" beberapa kali
   - **Lokasi install: `C:\xampp`** (pastikan!)
   - Klik "Install"

3. **Verifikasi**
   - Buka XAMPP Control Panel
   - Lokasi: `C:\xampp\xampp-control.exe`

---

## LANGKAH 2: Copy Folder Project

### A. Lokasi Project
```
Sumber: C:\Users\ASUS\OneDrive\Documents\IPPL_INDIVIDU1
Tujuan: C:\xampp\htdocs\IPPL_INDIVIDU1\
```

### B. Cara Copy (Windows Explorer)
1. Buka **Windows Explorer** (Win+E)
2. Navigasi ke: `C:\xampp\htdocs\`
3. Copy folder `IPPL_INDIVIDU1` ke sini
4. Hasil: `C:\xampp\htdocs\IPPL_INDIVIDU1\`

### C. Verifikasi
```
Buka C:\xampp\htdocs\IPPL_INDIVIDU1\
Dan pastikan ada file:
✅ index.php
✅ config/
✅ auth/
✅ database/
```

---

## LANGKAH 3: Start Services (XAMPP)

### A. Buka XAMPP Control Panel
```
Buka: C:\xampp\xampp-control.exe
```

### B. Start Apache & MySQL
```
Di XAMPP Control Panel, klik tombol "Start":

Apache:
[Start]  ← Klik sampai berubah jadi "Stop"
Status: Running

MySQL:
[Start]  ← Klik sampai berubah jadi "Stop"
Status: Running
```

### C. Verifikasi di Browser
```
Buka browser, ketik: http://localhost
Jika muncul halaman XAMPP, Apache sudah running ✅
```

---

## LANGKAH 4: Setup Database

### A. Buka phpMyAdmin
```
Di browser, buka: http://localhost/phpmyadmin

Atau: 
- Dari XAMPP Control Panel
- Klik tombol "Admin" di baris MySQL
```

### B. Buat Database Baru
```
Langkah:
1. Klik "New" di sebelah kiri
2. Database name: smart_queue
3. Collation: utf8mb4_unicode_ci
4. Klik tombol "Create"

Hasil: Database smart_queue berhasil dibuat ✅
```

### C. Import SQL Script
```
Langkah:
1. Pilih database: smart_queue (klik di kiri)
2. Buka tab: "Import"
3. Klik "Choose File"
4. Pilih: C:\xampp\htdocs\IPPL_INDIVIDU1\database\smart_queue.sql
5. Klik "Import"

Hasil: "Import has been successful" ✅
```

### D. Verifikasi Data
```
1. Di phpMyAdmin, expand "smart_queue"
2. Lihat tabel:
   ✅ users (harus ada)
   ✅ patients
   ✅ poli
   ✅ queues
   ✅ service_records
```

---

## LANGKAH 5: Konfigurasi Database (Optional)

**Jika database tidak bisa koneksi:**

### File: `config/database.php`
```
Buka: C:\xampp\htdocs\IPPL_INDIVIDU1\config\database.php

Pastikan setting (biasanya sudah benar):
define('DB_HOST', 'localhost');      ✅
define('DB_USER', 'root');           ✅
define('DB_PASS', '');               ✅ (kosong)
define('DB_NAME', 'smart_queue');    ✅
define('DB_PORT', '3306');           ✅
```

**Tidak perlu edit jika sudah sesuai dengan default XAMPP.**

---

## LANGKAH 6: Akses Aplikasi

### A. Buka di Browser

**URL Aplikasi:**
```
http://localhost/IPPL_INDIVIDU1/
```

**Yang akan terlihat:**
- Halaman monitoring antrean publik (tanpa login)
- Tombol "Daftar" dan "Login"
- Status antrean real-time

### B. Login ke Sistem

**URL Login:**
```
http://localhost/IPPL_INDIVIDU1/auth/login.php
```

**Gunakan test account:**

```
Role: Pasien
Email:    pasien@smartqueue.local
Password: pasien123456

Atau:

Role: Admin
Email:    admin@smartqueue.local
Password: admin123456
```

---

## LANGKAH 7: Test Fitur Utama

### A. Test Login Pasien
```
1. URL: http://localhost/IPPL_INDIVIDU1/auth/login.php
2. Email: pasien@smartqueue.local
3. Password: pasien123456
4. Klik "Login"
5. Hasil: Redirect ke dashboard pasien ✅
```

### B. Test Ambil Antrean
```
1. Login sebagai pasien
2. Klik "Ambil Antrean"
3. Pilih Poli: "Poli Umum"
4. Pilih Prioritas: "Normal"
5. Klik "Ambil Antrean"
6. Hasil: Muncul nomor antrean (misal: A001) ✅
```

### C. Test Admin Dashboard
```
1. URL: http://localhost/IPPL_INDIVIDU1/auth/login.php
2. Email: admin@smartqueue.local
3. Password: admin123456
4. Klik "Login"
5. Hasil: Dashboard dengan grafik Chart.js ✅
```

### D. Test Monitoring Realtime
```
1. Buka tab browser baru
2. URL: http://localhost/IPPL_INDIVIDU1/
3. Lihat status antrean
4. Di tab lain, ambil antrean sebagai pasien
5. Refresh halaman monitoring
6. Hasil: Status update otomatis ✅
```

---

## 🔗 URL Penting

| Halaman | URL |
|---------|-----|
| Landing/Monitoring | `http://localhost/IPPL_INDIVIDU1/` |
| Login | `http://localhost/IPPL_INDIVIDU1/auth/login.php` |
| Register | `http://localhost/IPPL_INDIVIDU1/auth/register.php` |
| Admin Dashboard | `http://localhost/IPPL_INDIVIDU1/admin/dashboard.php` |
| Pasien Dashboard | `http://localhost/IPPL_INDIVIDU1/pasien/dashboard.php` |
| Petugas Dashboard | `http://localhost/IPPL_INDIVIDU1/petugas/dashboard.php` |
| Dokter Dashboard | `http://localhost/IPPL_INDIVIDU1/dokter/dashboard.php` |
| phpMyAdmin | `http://localhost/phpmyadmin` |

---

## 📋 Checklist Setup Lengkap

```
XAMPP & Services:
☐ XAMPP installed di C:\xampp
☐ Apache running (XAMPP Control Panel)
☐ MySQL running (XAMPP Control Panel)
☐ http://localhost bisa diakses

Project Setup:
☐ Folder IPPL_INDIVIDU1 ada di C:\xampp\htdocs\
☐ File index.php ada
☐ Folder config/, auth/, api/, etc ada

Database:
☐ Database smart_queue dibuat
☐ SQL script sudah di-import
☐ Tabel users, patients, poli, queues ada
☐ Config database.php benar

Aplikasi:
☐ http://localhost/IPPL_INDIVIDU1/ bisa dibuka
☐ Halaman monitoring tampil
☐ Login bisa dilakukan
☐ Ambil antrean berfungsi
☐ Admin dashboard menampilkan grafik
```

Jika semua checklist ✅, aplikasi siap digunakan!

---

## ⚠️ Troubleshooting Common Issues

### 1. "Cannot connect to database"

**Gejala:** Error saat login
```
SQLSTATE[HY000]: General error: 2006 MySQL has gone away
```

**Solusi:**
```
1. Buka XAMPP Control Panel
2. Pastikan MySQL status "Running"
3. Jika belum, klik "Start" di MySQL
4. Tunggu 3-5 detik
5. Refresh halaman browser
```

---

### 2. "404 Not Found"

**Gejala:** `index.php not found`

**Solusi:**
```
1. Verifikasi lokasi folder: C:\xampp\htdocs\IPPL_INDIVIDU1\
2. Pastikan file index.php ada
3. Akses URL lengkap: http://localhost/IPPL_INDIVIDU1/
4. Jangan lupa: /IPPL_INDIVIDU1/ di URL
```

---

### 3. "Blank Page / White Screen"

**Gejala:** Halaman kosong, tidak ada error

**Solusi:**
```
1. Tekan F12 untuk buka Console (DevTools)
2. Lihat error messages
3. Cek file: C:\xampp\apache\logs\error.log
4. Verifikasi syntax PHP
```

---

### 4. "Login Failed / Incorrect Password"

**Gejala:** Tidak bisa login meskipun password benar

**Solusi:**
```
1. Verifikasi database sudah ter-import:
   - Buka phpMyAdmin
   - Pilih database smart_queue
   - Tab "Users" pada tabel users
   - Pastikan ada user: admin@smartqueue.local

2. Gunakan password default:
   - admin@smartqueue.local / admin123456
   - pasien@smartqueue.local / pasien123456
```

---

### 5. "AJAX tidak Update / Monitoring Tidak Bergerak"

**Gejala:** Monitoring halaman tidak update otomatis

**Solusi:**
```
1. Buka DevTools (F12)
2. Tab "Console"
3. Lihat error messages
4. Cek Network tab untuk api/queue_status.php
5. Pastikan API endpoint accessible
```

---

### 6. "Apache tidak bisa start"

**Gejala:** Apache button tetap merah, tidak mau jalan

**Solusi:**
```
1. Pastikan port 80 tidak dipakai:
   - Buka Command Prompt sebagai Administrator
   - Ketik: netstat -ano | findstr :80
   - Jika ada hasil, ada program lain pakai port 80

2. Ganti port di XAMPP:
   - Buka: C:\xampp\apache\conf\httpd.conf
   - Cari: Listen 80
   - Ubah jadi: Listen 8080
   - Akses: http://localhost:8080
```

---

### 7. "Grafik tidak muncul di dashboard admin"

**Gejala:** Dashboard kosong, tidak ada chart

**Solusi:**
```
1. Verifikasi data di database:
   - Buka phpMyAdmin
   - Tabel queues, lihat ada data atau tidak?
   
2. Jika tidak ada data:
   - Ambil antrean sebagai pasien dulu
   - Refresh admin dashboard

3. Jika ada data tapi grafik tidak muncul:
   - Buka DevTools (F12)
   - Lihat error di console
   - Mungkin Chart.js library tidak loaded
```

---

## 📞 Jika Masih Ada Masalah

1. **Baca dokumentasi:**
   - [QUICK_START.md](QUICK_START.md) - Quick reference
   - [docs/installation_guide.md](docs/installation_guide.md) - Detail lengkap
   - [README.md](README.md) - Overview proyek

2. **Check file logs:**
   - Apache: `C:\xampp\apache\logs\error.log`
   - MySQL: `C:\xampp\mysql\data\mysql.log`

3. **Coba setup otomatis:**
   - Jalankan: `setup.bat` atau `setup.ps1`
   - Script akan handle setup otomatis

---

**Selamat! Jika semua langkah berhasil, Smart Queue System siap digunakan!** 🎉

---

**Last Updated:** Juni 2026
**Version:** 1.0.0
