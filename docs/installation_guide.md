# Panduan Instalasi - Smart Queue System

## Persyaratan Sistem

- **Web Server**: Apache (XAMPP)
- **PHP**: Versi 7.4 atau lebih tinggi
- **Database**: MySQL 5.7 atau lebih tinggi
- **Browser**: Chrome, Firefox, Edge (versi terbaru)

## Langkah Instalasi

### 1. Setup XAMPP

#### Windows:
1. Download XAMPP dari [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Install XAMPP di drive C (misal: `C:\xampp`)
3. Jalankan XAMPP Control Panel
4. Start Apache dan MySQL

#### Linux/Mac:
```bash
# Linux (menggunakan package manager)
sudo apt-get install xampp-linux

# Mac (menggunakan Homebrew)
brew install xampp
```

### 2. Setup Database

1. Buka phpMyAdmin: `http://localhost/phpmyadmin`
2. Login dengan username: `root` (password kosong)
3. Buat database baru:
   - Nama database: `smart_queue`
   - Collation: `utf8mb4_unicode_ci`
4. Import file SQL:
   - Pilih database `smart_queue`
   - Tab `Import`
   - Pilih file `database/smart_queue.sql`
   - Klik `Go`

### 3. Upload File Aplikasi

1. Ekstrak folder `smart-queue-system` ke:
   ```
   C:\xampp\htdocs\  (Windows)
   /opt/lampp/htdocs/  (Linux)
   /Applications/XAMPP/htdocs/  (Mac)
   ```

2. Struktur folder akan menjadi:
   ```
   htdocs/
   └── smart-queue-system/
       ├── config/
       ├── api/
       ├── auth/
       ├── pasien/
       └── ... (folder lainnya)
   ```

### 4. Konfigurasi Koneksi Database

Edit file `config/database.php`:

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Password MySQL (kosong jika default)
define('DB_NAME', 'smart_queue');
define('DB_PORT', '3306');
?>
```

### 5. Akses Aplikasi

Buka browser dan akses:

```
http://localhost/smart-queue-system/
```

atau

```
http://localhost/smart-queue-system/auth/login.php
```

## Akun Test Default

### Admin
- **Email**: admin@smartqueue.local
- **Password**: admin123456

### Petugas
- **Email**: petugas1@smartqueue.local
- **Password**: petugas123456

### Dokter
- **Email**: dokter1@smartqueue.local
- **Password**: dokter123456

### Pasien
- **Email**: pasien@smartqueue.local
- **Password**: pasien123456

> **Catatan**: Password hash di database sudah disiapkan. Untuk mengubah password, edit di phpMyAdmin atau gunakan script khusus.

## Troubleshooting

### 1. Error "XAMPP tidak berjalan"
```bash
# Windows - Jalankan services.msc dan start Apache/MySQL
# Linux/Mac - Gunakan XAMPP Control Panel
```

### 2. Error koneksi database
```php
// Periksa di phpMyAdmin:
// 1. Database smart_queue sudah ada?
// 2. User root dapat login?
// 3. File database/smart_queue.sql sudah di-import?
```

### 3. Error "index.php not found"
```bash
# Pastikan struktur folder sudah benar:
htdocs/smart-queue-system/index.php
```

### 4. Session tidak bekerja
```php
// Edit php.ini:
// session.save_path = "/tmp" (Linux)
// session.save_path = "C:\Windows\Temp" (Windows)
```

### 5. Permission Denied
```bash
# Linux/Mac - Ubah permission folder:
chmod -R 755 /path/to/smart-queue-system
chmod -R 777 /path/to/smart-queue-system/assets
```

## Fitur Keamanan

✅ Password hashing dengan bcrypt
✅ Prepared statements (proteksi SQL Injection)
✅ Input sanitization (proteksi XSS)
✅ Session management dengan role-based access
✅ HTTPS ready (configure di production)

## Maintenance

### Backup Database
```bash
# Menggunakan mysqldump
mysqldump -u root smart_queue > backup_smart_queue.sql

# Menggunakan phpMyAdmin:
# 1. Pilih database smart_queue
# 2. Tab Export
# 3. Klik Go
```

### Restore Database
```bash
# Menggunakan mysql
mysql -u root smart_queue < backup_smart_queue.sql

# Menggunakan phpMyAdmin:
# 1. Pilih database smart_queue
# 2. Tab Import
# 3. Pilih file backup
# 4. Klik Go
```

### Log File
- Apache Log: `xampp/apache/logs/error.log`
- MySQL Log: `xampp/mysql/data/mysql.log`

## Dokumentasi Tambahan

- [README.md](README.md) - Pengenalan proyek
- [test_cases.md](test_cases.md) - Dokumentasi test case
- [PRD.md](PRD.md) - Product Requirements Document

---

**Versi Dokumentasi**: 1.0.0
**Last Updated**: Juni 2026
