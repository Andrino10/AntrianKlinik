# 🚀 QUICK START - Smart Queue System

**Panduan Cepat untuk Menjalankan Aplikasi**

---

## ⚙️ STEP 1: Download & Install XAMPP

### A. Download XAMPP
1. Buka: **https://www.apachefriends.org/**
2. Download versi **PHP 7.4+** atau **8.0+**
3. Pilih installer sesuai OS (Windows/Linux/Mac)

### B. Install XAMPP (Windows)
1. Jalankan installer XAMPP
2. Pilih lokasi instalasi: `C:\xampp` (default)
3. Pilih komponen: pastikan **Apache** dan **MySQL** dicentang
4. Finish dan jalankan XAMPP Control Panel

### C. Start Services
**Buka XAMPP Control Panel:**
```
Klik tombol "Start" untuk:
✅ Apache (port 80)
✅ MySQL (port 3306)
```

**Verifikasi:**
- Buka browser: `http://localhost`
- Jika muncul halaman XAMPP, instalasi berhasil ✅

---

## 📂 STEP 2: Setup Project File

### A. Lokasi Project
**Windows:**
```
Salin folder IPPL_INDIVIDU1 ke:
C:\xampp\htdocs\
```

**Hasil:**
```
C:\xampp\htdocs\IPPL_INDIVIDU1\
├── config/
├── api/
├── auth/
└── ... (folder lainnya)
```

### B. Verifikasi Struktur
Pastikan struktur folder sudah benar:
```
htdocs/
└── IPPL_INDIVIDU1/
    ├── index.php ✅
    ├── config/
    ├── auth/
    └── ...
```

---

## 🗄️ STEP 3: Setup Database

### A. Buka phpMyAdmin
```
1. Buka browser
2. Ketik: http://localhost/phpmyadmin
3. Login dengan:
   - Username: root
   - Password: (kosongkan)
```

### B. Buat Database
```
1. Klik "New" di kiri
2. Database name: smart_queue
3. Collation: utf8mb4_unicode_ci
4. Klik "Create"
```

### C. Import SQL Script
```
1. Pilih database: smart_queue
2. Tab "Import"
3. Klik "Choose File"
4. Pilih: IPPL_INDIVIDU1/database/smart_queue.sql
5. Klik "Import"
```

**Status:** Jika muncul "Import has been successful" ✅

### D. Verifikasi Data
```
1. Database smart_queue sudah ada? ✅
2. Tabel: users, patients, poli, queues ✅
3. Data seed sudah ada? ✅
```

---

## 🔧 STEP 4: Konfigurasi Database

### Edit File: `config/database.php`
```
File: C:\xampp\htdocs\IPPL_INDIVIDU1\config\database.php

Pastikan konfigurasi:
- DB_HOST: localhost ✅
- DB_USER: root ✅
- DB_PASS: (kosong) ✅
- DB_NAME: smart_queue ✅
- DB_PORT: 3306 ✅
```

**Tidak perlu edit jika setting sudah default.**

---

## 🌐 STEP 5: Akses Aplikasi

### A. Monitoring Halaman Publik (Tanpa Login)
```
URL: http://localhost/IPPL_INDIVIDU1/
```

Akan melihat:
- 📊 Nomor sedang dipanggil
- 📈 Status antrean real-time
- 🔘 Tombol Daftar & Login

### B. Login ke Sistem

**URL Login:**
```
http://localhost/IPPL_INDIVIDU1/auth/login.php
```

**Gunakan akun test:**

| Role | Email | Password |
|------|-------|----------|
| 👨‍💼 Admin | admin@smartqueue.local | admin123456 |
| 👤 Petugas | petugas1@smartqueue.local | petugas123456 |
| 👨‍⚕️ Dokter | dokter1@smartqueue.local | dokter123456 |
| 🧑 Pasien | pasien@smartqueue.local | pasien123456 |

---

## 🧪 STEP 6: Test Fitur Utama

### A. Test sebagai PASIEN
```
1. Login: pasien@smartqueue.local / pasien123456
2. Klik "Ambil Antrean"
3. Pilih Poli: Poli Umum
4. Klik "Ambil Antrean"
5. Lihat nomor antrean yang digenerate ✅
```

### B. Test sebagai PETUGAS
```
1. Login: petugas1@smartqueue.local / petugas123456
2. Lihat Dashboard (statistik)
3. Klik "Kelola Antrean"
4. Panggil pasien berikutnya ✅
```

### C. Test Monitoring Realtime
```
1. Buka di tab baru: http://localhost/IPPL_INDIVIDU1/
2. Lihat status antrean update otomatis (setiap 5 detik)
3. Saat petugas panggil pasien, monitoring update ✅
```

### D. Test Admin Dashboard
```
1. Login: admin@smartqueue.local / admin123456
2. Lihat Dashboard dengan grafik Chart.js ✅
3. Kelola Pengguna & Poli ✅
```

---

## 📋 Checklist Setup

- [ ] XAMPP installed & Apache/MySQL running
- [ ] Folder IPPL_INDIVIDU1 di `C:\xampp\htdocs\`
- [ ] Database `smart_queue` created
- [ ] SQL script imported
- [ ] `config/database.php` configured
- [ ] Can access: `http://localhost/IPPL_INDIVIDU1/`
- [ ] Can login dengan test accounts
- [ ] Monitoring page berfungsi

---

## ⚠️ Troubleshooting

### 1. "Cannot connect to database"
```
Solusi:
✅ Pastikan MySQL running di XAMPP Control Panel
✅ Verifikasi DB_HOST, DB_USER, DB_PASS di config/database.php
✅ Check di phpMyAdmin apakah database terbuat
```

### 2. "404 Not Found - index.php"
```
Solusi:
✅ Cek lokasi folder: C:\xampp\htdocs\IPPL_INDIVIDU1\
✅ Pastikan index.php ada di root folder
✅ Akses dengan path lengkap: /IPPL_INDIVIDU1/
```

### 3. "Blank page / White screen"
```
Solusi:
✅ Cek PHP errors di: C:\xampp\apache\logs\error.log
✅ Enable error display di php.ini
✅ Pastikan semua file PHP ada & syntax benar
```

### 4. "Login gagal"
```
Solusi:
✅ Verifikasi database user sudah ter-import
✅ Cek tabel users di phpMyAdmin
✅ Gunakan password default: admin123456, dll
```

### 5. "AJAX tidak update / monitoring not working"
```
Solusi:
✅ Buka browser console (F12) cek error
✅ Pastikan api/queue_status.php accessible
✅ Check MySQL connection di API
```

---

## 🔗 URL Penting

| Halaman | URL |
|---------|-----|
| 🏠 Landing/Monitoring | `http://localhost/IPPL_INDIVIDU1/` |
| 🔐 Login | `http://localhost/IPPL_INDIVIDU1/auth/login.php` |
| 📝 Register | `http://localhost/IPPL_INDIVIDU1/auth/register.php` |
| 📊 Admin Dashboard | `http://localhost/IPPL_INDIVIDU1/admin/dashboard.php` |
| 🧑 Pasien Dashboard | `http://localhost/IPPL_INDIVIDU1/pasien/dashboard.php` |
| 👤 Petugas Dashboard | `http://localhost/IPPL_INDIVIDU1/petugas/dashboard.php` |
| 👨‍⚕️ Dokter Dashboard | `http://localhost/IPPL_INDIVIDU1/dokter/dashboard.php` |
| 🗄️ phpMyAdmin | `http://localhost/phpmyadmin` |

---

## 📚 Dokumentasi Lengkap

- **README.md** - Overview proyek
- **docs/installation_guide.md** - Instalasi detail
- **docs/test_cases.md** - Dokumentasi test
- **prd.md** - Spesifikasi produk

---

## ✅ Aplikasi Siap Digunakan!

Jika semua langkah sudah dilakukan dengan benar, Smart Queue System sudah siap dijalankan dan ditest.

**Hubungi support jika ada masalah!** 📞

---

**Last Updated:** Juni 2026
**Version:** 1.0.0
