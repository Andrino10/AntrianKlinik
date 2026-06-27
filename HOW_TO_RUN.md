# 🚀 CARA MENJALANKAN SMART QUEUE SYSTEM

**Panduan Singkat & Jelas untuk Menjalankan Aplikasi**

---

## ⚡ PILIHAN 1: Setup Otomatis (Recommended)

Jika Anda sudah install XAMPP, cara paling mudah adalah:

### A. Batch Script (Windows CMD)
```bash
1. Buka Command Prompt (cmd.exe) sebagai Administrator
2. Navigate ke folder project:
   cd C:\xampp\htdocs\IPPL_INDIVIDU1
3. Jalankan:
   setup.bat
```

### B. PowerShell Script (Modern)
```powershell
1. Buka PowerShell sebagai Administrator
2. Jalankan:
   Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
3. Navigate ke folder:
   cd C:\xampp\htdocs\IPPL_INDIVIDU1
4. Jalankan:
   .\setup.ps1
```

**Script akan:**
- ✅ Check XAMPP installation
- ✅ Verify project files
- ✅ Start Apache & MySQL
- ✅ Create database automatically
- ✅ Show test accounts
- ✅ Open browser otomatis

---

## 📋 PILIHAN 2: Setup Manual Step-by-Step

Jika ingin tahu setiap langkahnya, ikuti:
**[RUNNING_GUIDE.md](RUNNING_GUIDE.md)**

Dokumentasi lengkap berisi:
- Instalasi XAMPP
- Copy folder ke htdocs
- Setup database di phpMyAdmin
- Import SQL script
- Test setiap fitur

---

## ⚡ QUICK START (TL;DR)

Jika Anda sudah berpengalaman:

```
1. Copy folder IPPL_INDIVIDU1 ke C:\xampp\htdocs\

2. Start XAMPP (Apache & MySQL):
   - Buka XAMPP Control Panel
   - Klik "Start" untuk Apache & MySQL

3. Buat database:
   - Buka: http://localhost/phpmyadmin
   - Buat database: smart_queue
   - Import: IPPL_INDIVIDU1/database/smart_queue.sql

4. Akses aplikasi:
   - Landing page: http://localhost/IPPL_INDIVIDU1/
   - Login: http://localhost/IPPL_INDIVIDU1/auth/login.php

5. Login dengan test account:
   - Pasien: pasien@smartqueue.local / pasien123456
   - Admin: admin@smartqueue.local / admin123456
```

---

## 🎯 Apa yang Akan Terjadi Setelah Setup?

### A. Halaman Monitoring (Publik - Tanpa Login)
```
URL: http://localhost/IPPL_INDIVIDU1/

Fitur:
✅ Lihat nomor sedang dipanggil
✅ Lihat status antrean real-time
✅ Update otomatis setiap 5 detik
✅ Tombol Daftar & Login
```

### B. Login Sistem
```
URL: http://localhost/IPPL_INDIVIDU1/auth/login.php

Test Accounts:
- Pasien: pasien@smartqueue.local / pasien123456
- Petugas: petugas1@smartqueue.local / petugas123456
- Dokter: dokter1@smartqueue.local / dokter123456
- Admin: admin@smartqueue.local / admin123456
```

### C. Dashboard per Role
```
Pasien Dashboard:
- Lihat status antrean hari ini
- Ambil antrean baru
- Lihat riwayat kunjungan

Petugas Dashboard:
- Lihat statistik antrean
- Kelola pemanggilan pasien
- Lihat data pasien

Admin Dashboard:
- Statistik lengkap
- Grafik Chart.js (3 grafik)
- Manajemen pengguna & poli
```

---

## 📊 Struktur Folder

```
IPPL_INDIVIDU1/
├── config/              → Database config
├── api/                 → REST API endpoints
├── auth/                → Login/Register
├── pasien/              → Pasien module
├── petugas/             → Petugas module
├── dokter/              → Dokter module
├── admin/               → Admin module
├── assets/              → CSS, JS, Images
├── includes/            → Helper functions
├── database/            → SQL schema
├── docs/                → Documentation
├── QUICK_START.md       → Ringkasan cepat
├── RUNNING_GUIDE.md     → Panduan manual detail
├── setup.bat            → Script otomasi (Windows)
├── setup.ps1            → Script otomasi (PowerShell)
└── index.php            → Landing page
```

---

## 🔗 URL Penting

| Halaman | URL |
|---------|-----|
| **Monitoring** (Publik) | `http://localhost/IPPL_INDIVIDU1/` |
| **Login** | `http://localhost/IPPL_INDIVIDU1/auth/login.php` |
| **Register** | `http://localhost/IPPL_INDIVIDU1/auth/register.php` |
| **Pasien Dashboard** | `http://localhost/IPPL_INDIVIDU1/pasien/dashboard.php` |
| **Petugas Dashboard** | `http://localhost/IPPL_INDIVIDU1/petugas/dashboard.php` |
| **Admin Dashboard** | `http://localhost/IPPL_INDIVIDU1/admin/dashboard.php` |
| **phpMyAdmin** | `http://localhost/phpmyadmin` |

---

## ✅ Checklist Setup

Sebelum mulai, pastikan:

- [ ] XAMPP sudah terinstall di `C:\xampp`
- [ ] Folder `IPPL_INDIVIDU1` sudah di `C:\xampp\htdocs\`
- [ ] XAMPP Control Panel sudah buka
- [ ] Apache & MySQL status "Running"
- [ ] Browser terbuka (Chrome/Firefox/Edge)

---

## 🆘 Error? Cek Ini

### 1. "Cannot connect to database"
```
→ Pastikan MySQL running di XAMPP Control Panel
→ Cek database smart_queue sudah ada
```

### 2. "404 Not Found"
```
→ Pastikan folder di: C:\xampp\htdocs\IPPL_INDIVIDU1\
→ Akses dengan path lengkap: /IPPL_INDIVIDU1/
```

### 3. "Login Failed"
```
→ Gunakan test accounts yang sudah disiapkan
→ Cek database sudah ter-import
```

### 4. Masalah lain?
```
→ Buka: RUNNING_GUIDE.md (Troubleshooting section)
→ Atau: docs/installation_guide.md
```

---

## 📚 Dokumentasi Lengkap

Jika butuh informasi lebih detail:

| File | Isi |
|------|-----|
| **QUICK_START.md** | Ringkasan setup (6 step utama) |
| **RUNNING_GUIDE.md** | Panduan manual step-by-step + troubleshooting |
| **README.md** | Overview proyek & fitur lengkap |
| **docs/installation_guide.md** | Instalasi detail untuk semua OS |
| **docs/test_cases.md** | 30+ test case untuk QA |
| **prd.md** | Spesifikasi produk lengkap |

---

## 🎉 Selesai!

**Jika semua langkah sudah dilakukan, aplikasi siap digunakan:**

```
✅ Buka: http://localhost/IPPL_INDIVIDU1/
✅ Login: pasien@smartqueue.local / pasien123456
✅ Test: Ambil antrean
✅ Cek: Admin dashboard dengan grafik
```

---

## 💡 Tips

1. **Jangan lupa start XAMPP** sebelum mengakses aplikasi
2. **Gunakan test accounts** yang sudah disediakan
3. **Cek DevTools (F12)** jika ada error di browser
4. **Pastikan folder path benar** saat copy
5. **Baca QUICK_START.md** untuk referensi cepat

---

**Semua siap! Selamat menggunakan Smart Queue System!** 🚀

**Version:** 1.0.0  
**Last Updated:** Juni 2026
