# Smart Queue System

**Sistem Antrean Digital Klinik/Puskesmas**

Sebuah aplikasi web manajemen antrean berbasis PHP Native yang dirancang untuk menggantikan sistem antrean konvensional di klinik dan puskesmas dengan solusi digital yang efisien.

## 📋 Informasi Proyek

- **Versi**: 1.0.0
- **Status**: Development
- **Mata Kuliah**: Implementasi Pengujian Perangkat Lunak
- **Teknologi**: HTML5, CSS3, Vanilla JavaScript, PHP 7.4+, MySQL, AJAX

## 🎯 Fitur Utama

### Untuk Pasien
- ✅ Registrasi dan login akun
- ✅ Pengambilan nomor antrean online
- ✅ Monitoring status antrean secara realtime
- ✅ Melihat estimasi waktu tunggu
- ✅ Riwayat kunjungan
- ✅ Pembatalan antrean

### Untuk Petugas
- ✅ Dashboard statistik harian
- ✅ Manajemen pemanggilan pasien
- ✅ Monitoring seluruh antrean
- ✅ Data manajemen pasien
- ✅ Prioritas layanan terstruktur

### Untuk Dokter
- ✅ Daftar pasien di poli
- ✅ Rekam pemeriksaan pasien
- ✅ Riwayat layanan

### Untuk Admin
- ✅ Dashboard dengan grafik statistik Chart.js
- ✅ Manajemen pengguna (user management)
- ✅ Manajemen poli (clinic management)
- ✅ Laporan dan analisis performa

## 📁 Struktur Folder

```
smart-queue-system/
├── config/                 # Konfigurasi database
├── api/                    # REST API endpoints
├── auth/                   # Autentikasi (login, logout, register)
├── pasien/                 # Modul pasien
├── petugas/                # Modul petugas
├── dokter/                 # Modul dokter
├── admin/                  # Modul admin
├── includes/               # Helper functions & middleware
├── assets/
│   ├── css/                # Stylesheet
│   ├── js/                 # JavaScript files
│   └── img/                # Images & assets
├── database/               # SQL schema
├── docs/                   # Dokumentasi
└── index.php              # Landing page
```

## 🚀 Panduan Quick Start

### Persyaratan
- Apache (XAMPP)
- PHP 7.4+
- MySQL 5.7+
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

1. **Clone/Extract ke htdocs**
   ```bash
   # Windows
   C:\xampp\htdocs\smart-queue-system\
   
   # Linux
   /opt/lampp/htdocs/smart-queue-system/
   ```

2. **Setup Database**
   - Buka phpMyAdmin: `http://localhost/phpmyadmin`
   - Buat database: `smart_queue`
   - Import: `database/smart_queue.sql`

3. **Konfigurasi Database**
   - Edit `config/database.php`
   - Sesuaikan DB_HOST, DB_USER, DB_PASS, DB_NAME

4. **Akses Aplikasi**
   ```
   http://localhost/smart-queue-system/
   ```

### Akun Test Default

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@smartqueue.local | admin123456 |
| Petugas | petugas1@smartqueue.local | petugas123456 |
| Dokter | dokter1@smartqueue.local | dokter123456 |
| Pasien | pasien@smartqueue.local | pasien123456 |

## 🔐 Fitur Keamanan

✅ Password hashing dengan bcrypt
✅ Prepared statements (anti SQL Injection)
✅ Input sanitization (anti XSS)
✅ Session management
✅ Role-based access control
✅ CSRF protection ready

## 📊 Test Case

Total 30+ test case telah didokumentasikan mencakup:
- Black Box Testing
- Equivalence Partitioning
- Boundary Value Analysis
- User Acceptance Testing

**Lihat**: [docs/test_cases.md](docs/test_cases.md)

## 📖 Dokumentasi

- [Product Requirements Document](prd.md) - Spesifikasi lengkap produk
- [Panduan Instalasi](docs/installation_guide.md) - Step-by-step setup
- [Test Cases](docs/test_cases.md) - Dokumentasi pengujian

## 🛠️ Teknologi yang Digunakan

### Frontend
- **HTML5** - Struktur halaman
- **CSS3** - Styling & responsive design
- **Vanilla JavaScript** - Interaktivitas & AJAX
- **Chart.js** - Grafik & visualisasi data

### Backend
- **PHP 7.4+** - Server-side logic
- **MySQL** - Database management
- **PDO** - Secure database access
- **AJAX** - Real-time updates

### Tools & Libraries
- **XAMPP** - Local development environment
- **Git** - Version control
- **phpMyAdmin** - Database management

## 📝 Fitur AJAX

- ✅ Real-time monitoring antrean (polling setiap 5 detik)
- ✅ Update status antrean tanpa refresh
- ✅ Form submission tanpa page reload
- ✅ Notifikasi toast messages

## 🎨 UI/UX Features

- ✅ Responsive design (mobile, tablet, desktop)
- ✅ Dark sidebar navigation
- ✅ Color-coded status badges
- ✅ Animated transitions
- ✅ User-friendly forms
- ✅ Dashboard statistics cards
- ✅ Data tables dengan styling

## 📊 Database Schema

### Tabel Utama
- `users` - Pengguna sistem (admin, petugas, dokter, pasien)
- `patients` - Data pasien
- `poli` - Daftar poli/unit layanan
- `queues` - Antrean
- `service_records` - Riwayat pelayanan

### Views
- `v_queue_summary_today` - Ringkasan antrean hari ini per poli

## 🔄 Alur Aplikasi

### Pendaftaran Pasien
1. Akses `/auth/register.php`
2. Isi form registrasi (nama, NIK, email, password, dll)
3. Validasi client & server-side
4. Data disimpan ke database
5. Redirect ke login

### Pengambilan Antrean
1. Login sebagai pasien
2. Akses "Ambil Antrean"
3. Pilih poli & kategori prioritas
4. Sistem generate nomor antrean otomatis
5. Tampil estimasi waktu tunggu
6. Nomor disimpan ke database

### Pemanggilan Pasien
1. Petugas login & akses dashboard
2. Pilih poli
3. Klik "Panggil Berikutnya"
4. Sistem urutkan berdasarkan prioritas
5. Update status ke 'dipanggil'
6. Halaman monitoring update otomatis via AJAX

## 🧪 Pengujian

Sistem dapat diuji menggunakan:
- **Manual Testing** - Via browser
- **Automated Testing** - Test case suite
- **Load Testing** - Performance check
- **UAT** - User acceptance testing

Lihat: [Test Cases Documentation](docs/test_cases.md)

## 📈 KPI Keberhasilan

| Metrik | Target |
|--------|--------|
| Waktu registrasi pasien | < 3 menit |
| Akurasi nomor antrean | 100% |
| Latensi AJAX polling | ≤ 5 detik |
| Tingkat login berhasil | 100% |
| Test case success rate | ≥ 90% |

## 🤝 Kontribusi

Untuk kontribusi atau melaporkan bug, silahkan:
1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Open Pull Request

## ⚠️ Limitations & Known Issues

- Notifikasi hanya via halaman web (tidak ada SMS/Push)
- Deployment hanya untuk XAMPP lokal
- Tidak ada multi-clinic support
- Tidak ada integrasi BPJS/SIMRS

## 📅 Maintenance

### Backup Database
```bash
mysqldump -u root smart_queue > backup.sql
```

### Restore Database
```bash
mysql -u root smart_queue < backup.sql
```

## 📞 Support

Untuk bantuan atau pertanyaan:
- Lihat dokumentasi di folder `docs/`
- Cek [installation_guide.md](docs/installation_guide.md)
- Review [PRD.md](prd.md) untuk spesifikasi lengkap

## 📄 Lisensi

Proyek ini adalah tugas akademis untuk mata kuliah Implementasi Pengujian Perangkat Lunak.

## 👨‍💻 Author

**Tim Pengembang Smart Queue System**
- Mata Kuliah: Implementasi Pengujian Perangkat Lunak
- Universitas: [Nama Universitas]
- Tahun: 2026

---

**Versi**: 1.0.0
**Last Updated**: Juni 2026
**Status**: Production Ready ✅
