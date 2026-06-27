# Product Requirements Document (PRD)
# Smart Queue System — Sistem Antrean Digital Klinik/Puskesmas

**Versi:** 1.0.0
**Tanggal:** Juni 2026
**Status:** Draft
**Mata Kuliah:** Implementasi Pengujian Perangkat Lunak
**Teknologi:** HTML5 · CSS3 · Vanilla JavaScript · PHP Native · MySQL · AJAX · XAMPP

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Latar Belakang](#2-latar-belakang)
3. [Tujuan Produk](#3-tujuan-produk)
4. [Ruang Lingkup](#4-ruang-lingkup)
5. [Aktor & Hak Akses](#5-aktor--hak-akses)
6. [Fitur & Persyaratan Fungsional](#6-fitur--persyaratan-fungsional)
7. [Persyaratan Non-Fungsional](#7-persyaratan-non-fungsional)
8. [Desain Database](#8-desain-database)
9. [Alur Sistem (System Flow)](#9-alur-sistem-system-flow)
10. [Desain UI/UX](#10-desain-uiux)
11. [Struktur Folder Proyek](#11-struktur-folder-proyek)
12. [Implementasi Pengujian Perangkat Lunak](#12-implementasi-pengujian-perangkat-lunak)
13. [Kriteria Penerimaan (Acceptance Criteria)](#13-kriteria-penerimaan-acceptance-criteria)
14. [Dokumentasi & Deliverable](#14-dokumentasi--deliverable)
15. [Asumsi & Batasan](#15-asumsi--batasan)
16. [Glosarium](#16-glosarium)

---

## 1. Ringkasan Eksekutif

Smart Queue System adalah aplikasi web manajemen antrean digital untuk klinik dan puskesmas. Sistem ini menggantikan proses antrean konvensional berbasis kertas dengan solusi digital yang memungkinkan pasien mendaftar secara online, memantau posisi antrean secara realtime, dan menerima notifikasi giliran pelayanan. Aplikasi ini dikembangkan sebagai objek penelitian pada mata kuliah Implementasi Pengujian Perangkat Lunak.

---

## 2. Latar Belakang

### 2.1 Permasalahan Saat Ini

Klinik dan puskesmas yang masih menggunakan sistem antrean konvensional menghadapi berbagai permasalahan:

| Permasalahan | Dampak |
|---|---|
| Pasien wajib hadir fisik untuk ambil nomor antrean | Penumpukan dan kerumunan di ruang tunggu |
| Tidak ada informasi estimasi waktu tunggu | Ketidakpastian jadwal pasien |
| Pencatatan manual rentan kesalahan | Inkonsistensi data antrean |
| Tidak ada sistem prioritas terstruktur | Pasien lansia/darurat tidak terlayani optimal |
| Petugas sulit memantau antrean secara menyeluruh | Pengelolaan antrean tidak efisien |

### 2.2 Solusi yang Diusulkan

Sistem antrean digital berbasis web yang memungkinkan:

- Pendaftaran dan pengambilan nomor antrean secara online.
- Pemantauan posisi antrean secara realtime via AJAX.
- Kalkulasi estimasi waktu tunggu otomatis.
- Manajemen prioritas antrean (lansia, ibu hamil, disabilitas, darurat).
- Dashboard administrasi dengan statistik dan grafik harian.

---

## 3. Tujuan Produk

### 3.1 Tujuan Bisnis

1. Mengurangi kepadatan fisik di ruang tunggu klinik/puskesmas.
2. Meningkatkan efisiensi waktu pelayanan pasien.
3. Mempermudah petugas dalam mengelola alur antrean harian.
4. Menyediakan data statistik untuk evaluasi kualitas pelayanan.

### 3.2 Tujuan Akademis

1. Menyediakan sistem yang cukup kompleks sebagai objek implementasi pengujian perangkat lunak tingkat perguruan tinggi.
2. Memfasilitasi penerapan metode Black Box Testing, Equivalence Partitioning, Boundary Value Analysis, dan User Acceptance Testing (UAT).
3. Menghasilkan minimal 30 test case yang terdokumentasi dengan baik.

### 3.3 Indikator Keberhasilan (KPI)

| KPI | Target |
|---|---|
| Waktu registrasi pasien | < 3 menit |
| Akurasi nomor antrean (tanpa duplikasi) | 100% |
| Latensi update realtime (AJAX polling) | ≤ 5 detik |
| Tingkat keberhasilan login yang valid | 100% |
| Cakupan test case yang lulus (pass) | ≥ 90% |

---

## 4. Ruang Lingkup

### 4.1 Dalam Ruang Lingkup (In Scope)

- Modul registrasi dan autentikasi pengguna (pasien, petugas, dokter, admin).
- Manajemen antrean per poli: buat, pantau, batalkan, selesaikan.
- Sistem penomoran antrean otomatis dengan format per poli.
- Antrean prioritas: lansia, ibu hamil, penyandang disabilitas, pasien darurat.
- Estimasi waktu tunggu berbasis kalkulasi jumlah antrian × rata-rata waktu layanan.
- Monitoring antrean realtime menggunakan AJAX polling.
- Dashboard admin dengan statistik harian dan grafik Chart.js.
- Riwayat kunjungan pasien.
- Dokumentasi pengujian perangkat lunak lengkap.

### 4.2 Di Luar Ruang Lingkup (Out of Scope)

- Integrasi dengan sistem BPJS atau rekam medis elektronik.
- Notifikasi SMS/WhatsApp/Push Notification ke perangkat pasien.
- Fitur telemedisin atau konsultasi online.
- Pembayaran online.
- Aplikasi mobile native (Android/iOS).
- Deployment ke server publik (cukup XAMPP lokal).

---

## 5. Aktor & Hak Akses

### 5.1 Pasien

Pengguna akhir yang mendaftarkan diri dan menggunakan layanan klinik.

| Aksi | Deskripsi |
|---|---|
| Registrasi akun | Membuat akun baru dengan data diri lengkap |
| Login | Masuk ke sistem menggunakan email dan password |
| Ambil nomor antrean | Memilih poli dan mendapatkan nomor antrean otomatis |
| Lihat status antrean | Memantau nomor yang sedang dipanggil dan posisi diri |
| Lihat estimasi waktu | Melihat perkiraan waktu pelayanan berdasarkan posisi antrean |
| Batalkan antrean | Membatalkan nomor antrean yang sudah diambil |
| Riwayat kunjungan | Melihat histori kunjungan ke poli sebelumnya |

### 5.2 Petugas Administrasi

Staf front-office yang mengelola alur antrean harian.

| Aksi | Deskripsi |
|---|---|
| Login | Masuk ke sistem |
| Lihat seluruh antrean | Memantau semua antrean aktif semua poli |
| Kelola data pasien | Melihat dan memperbarui data pasien terdaftar |
| Panggil pasien | Memanggil nomor antrean berikutnya |
| Panggil ulang | Memanggil kembali pasien yang tidak merespons |
| Tandai tidak hadir | Mengubah status pasien menjadi "Tidak Hadir" |
| Batalkan antrean | Membatalkan antrean atas permintaan pasien |
| Ubah status antrean | Mengubah status antrean secara manual |
| Lihat statistik harian | Melihat ringkasan antrean hari ini |

### 5.3 Dokter

Tenaga medis yang melayani pasien di poli.

| Aksi | Deskripsi |
|---|---|
| Login | Masuk ke sistem |
| Lihat daftar pasien | Melihat daftar pasien yang dijadwalkan di poli-nya |
| Panggil pasien berikutnya | Memanggil pasien dari daftar tunggu poli |
| Tandai selesai | Menandai bahwa pemeriksaan pasien telah selesai |
| Lihat riwayat pelayanan | Melihat histori pasien yang telah dilayani |

### 5.4 Admin Sistem

Pengelola sistem dengan akses penuh.

| Aksi | Deskripsi |
|---|---|
| Semua akses petugas | Mewarisi seluruh hak akses petugas |
| Kelola data poli | Tambah, edit, hapus data poli |
| Kelola pengguna | Tambah, edit, nonaktifkan akun pengguna |
| Lihat laporan | Mengakses laporan dan statistik sistem |

---

## 6. Fitur & Persyaratan Fungsional

### FR-01 — Modul Registrasi Pasien

**Deskripsi:** Pasien dapat membuat akun baru melalui form registrasi.

**Input yang Diperlukan:**

| Field | Tipe | Validasi |
|---|---|---|
| Nama Lengkap | Text | Wajib diisi, min. 3 karakter |
| NIK | Text | Wajib diisi, tepat 16 digit angka |
| Tanggal Lahir | Date | Wajib diisi, tidak boleh masa depan |
| Jenis Kelamin | Select | Wajib dipilih (Laki-laki / Perempuan) |
| Nomor Telepon | Text | Wajib diisi, format valid |
| Email | Email | Wajib diisi, format valid, unik di database |
| Password | Password | Wajib diisi, min. 8 karakter |
| Konfirmasi Password | Password | Harus sama persis dengan Password |
| Alamat | Textarea | Wajib diisi |

**Aturan Bisnis:**
- Password disimpan menggunakan `password_hash()` PHP (bcrypt).
- NIK hanya boleh terdaftar satu kali.
- Email hanya boleh terdaftar satu kali.
- Setelah registrasi berhasil, pengguna diarahkan ke halaman login.

---

### FR-02 — Modul Autentikasi (Login & Logout)

**Deskripsi:** Pengguna dapat masuk dan keluar dari sistem.

**Input Login:**

| Field | Validasi |
|---|---|
| Email | Wajib diisi, harus terdaftar di database |
| Password | Wajib diisi, harus cocok dengan hash di database |

**Aturan Bisnis:**
- Session PHP dibuat saat login berhasil.
- Role pengguna menentukan halaman redirect setelah login:
  - `pasien` → Dashboard Pasien
  - `petugas` → Dashboard Petugas
  - `dokter` → Dashboard Dokter
  - `admin` → Dashboard Admin
- Session dihapus saat logout.
- Halaman yang memerlukan autentikasi wajib memeriksa session; jika tidak ada, redirect ke halaman login.

---

### FR-03 — Modul Pengambilan Nomor Antrean

**Deskripsi:** Pasien terdaftar dapat mengambil nomor antrean untuk poli yang dipilih.

**Pilihan Poli:**

| Poli | Kode | Contoh Nomor |
|---|---|---|
| Poli Umum | A | A001, A002, A003 |
| Poli Gigi | G | G001, G002, G003 |
| Poli Anak | K | K001, K002, K003 |
| Poli Kandungan | KD | KD001, KD002 |
| Poli Penyakit Dalam | PD | PD001, PD002 |

**Aturan Bisnis:**
- Nomor antrean dibuat otomatis secara sekuensial per poli per hari.
- Satu pasien hanya boleh memiliki satu antrean aktif (status: `menunggu` atau `dipanggil`) dalam satu hari.
- Nomor antrean di-reset ke 001 setiap hari baru.
- Sistem menggunakan transaksi database untuk mencegah nomor antrean ganda (race condition).
- Pasien dapat memilih kategori prioritas jika berlaku.

---

### FR-04 — Modul Estimasi Waktu Tunggu

**Deskripsi:** Sistem menghitung dan menampilkan estimasi waktu pelayanan.

**Formula:**

```
Estimasi Waktu = Jumlah Antrean Di Depan × Rata-rata Waktu Pelayanan Per Pasien
Estimasi Jam Pelayanan = Jam Sekarang + Estimasi Waktu
```

**Konfigurasi Default:**
- Rata-rata waktu pelayanan: **10 menit per pasien**.
- Nilai ini dapat dikonfigurasi oleh admin.

**Contoh Output:**
```
Posisi antrean Anda: 12
Estimasi waktu tunggu: 120 menit
Estimasi jam pelayanan: 10.45 WIB
```

---

### FR-05 — Modul Monitoring Antrean Realtime

**Deskripsi:** Halaman publik yang menampilkan status antrean terkini secara otomatis tanpa refresh halaman.

**Informasi yang Ditampilkan:**

| Elemen | Deskripsi |
|---|---|
| Nomor Sedang Dipanggil | Nomor antrean yang saat ini dalam pelayanan |
| Nomor Berikutnya | Nomor antrean yang akan dipanggil selanjutnya |
| Antrean Tersisa | Total pasien yang masih menunggu |
| Total Pasien Hari Ini | Total antrean yang terdaftar hari ini |

**Spesifikasi Teknis:**
- Data diperbarui menggunakan **AJAX polling** setiap 5 detik.
- Endpoint: `api/queue_status.php` mengembalikan data JSON.
- Tidak memerlukan login untuk mengakses halaman monitoring publik.

---

### FR-06 — Modul Pemanggilan Pasien

**Deskripsi:** Petugas/dokter dapat mengelola pemanggilan pasien sesuai urutan antrean.

**Aksi yang Tersedia:**

| Aksi | Status Awal | Status Akhir |
|---|---|---|
| Panggil Berikutnya | Menunggu | Dipanggil |
| Panggil Ulang | Dipanggil | Dipanggil (notifikasi ulang) |
| Masuk Pemeriksaan | Dipanggil | Dalam Pemeriksaan |
| Selesai | Dalam Pemeriksaan | Selesai |
| Tidak Hadir | Dipanggil | Tidak Hadir |
| Batalkan | Menunggu / Dipanggil | Dibatalkan |

**Status Antrean (Lengkap):**

```
menunggu → dipanggil → dalam_pemeriksaan → selesai
                    ↘ tidak_hadir
         ↘ dibatalkan
```

---

### FR-07 — Modul Antrean Prioritas

**Deskripsi:** Pasien dengan kondisi tertentu mendapatkan prioritas dalam antrean.

**Kategori Prioritas:**

| Kategori | Kode | Keterangan |
|---|---|---|
| Normal | `normal` | Urutan standar |
| Lansia | `lansia` | Usia ≥ 60 tahun |
| Ibu Hamil | `hamil` | Kondisi kehamilan |
| Penyandang Disabilitas | `disabilitas` | Kondisi fisik/mental tertentu |
| Pasien Darurat | `darurat` | Kondisi medis mendesak |

**Aturan Urutan Prioritas:**
1. `darurat` — dipanggil paling awal
2. `lansia`, `hamil`, `disabilitas` — dipanggil setelah darurat, sebelum normal
3. `normal` — urutan standar (first-come, first-served)

---

### FR-08 — Modul Riwayat Kunjungan

**Deskripsi:** Pasien dapat melihat riwayat kunjungan sebelumnya.

**Data yang Ditampilkan:**

| Field | Deskripsi |
|---|---|
| Tanggal Kunjungan | Tanggal antrean diambil |
| Poli | Nama poli yang dikunjungi |
| Nomor Antrean | Nomor antrean yang diperoleh |
| Status | Status akhir pelayanan |
| Estimasi Waktu | Estimasi waktu yang diberikan saat daftar |

---

### FR-09 — Dashboard Admin

**Deskripsi:** Halaman ringkasan statistik harian untuk admin dan petugas.

**Card Statistik:**

| Metrik | Deskripsi |
|---|---|
| Total Pasien Terdaftar | Akumulasi semua pasien di sistem |
| Total Antrean Hari Ini | Antrean yang dibuat pada tanggal hari ini |
| Pasien Selesai Dilayani | Antrean dengan status `selesai` hari ini |
| Pembatalan Antrean | Antrean dengan status `dibatalkan` hari ini |
| Pasien Tidak Hadir | Antrean dengan status `tidak_hadir` hari ini |

**Grafik (Chart.js):**
- Bar chart: jumlah antrean per poli hari ini.
- Line chart: tren antrean 7 hari terakhir.
- Pie/Doughnut chart: distribusi status antrean hari ini.

---

## 7. Persyaratan Non-Fungsional

### 7.1 Keamanan

| Persyaratan | Implementasi |
|---|---|
| Hashing password | `password_hash()` dengan algoritma bcrypt |
| Proteksi SQL Injection | Prepared statements PDO / MySQLi |
| Proteksi XSS | `htmlspecialchars()` pada semua output |
| Session management | PHP session dengan regenerasi setelah login |
| Validasi input | Validasi di sisi klien (JavaScript) DAN sisi server (PHP) |
| Akses kontrol | Middleware pengecekan role pada setiap halaman terbatas |

### 7.2 Performa

- Halaman utama dimuat dalam < 3 detik pada koneksi lokal XAMPP.
- AJAX polling tidak menyebabkan memory leak pada browser.
- Query database menggunakan indeks pada kolom `tanggal` dan `poli_id`.

### 7.3 Ketersediaan

- Sistem berjalan di lingkungan XAMPP lokal (Apache + MySQL).
- Kompatibel dengan browser modern: Chrome, Firefox, Edge (versi terbaru).

### 7.4 Pemeliharaan

- Kode PHP diorganisir dengan struktur folder yang konsisten.
- Setiap file memiliki komentar header yang menjelaskan fungsinya.
- File konfigurasi database terpisah dari logika aplikasi (`config/database.php`).
- Tidak ada hardcoded credential selain di file konfigurasi.

### 7.5 Responsivitas

- Tampilan mendukung tiga breakpoint: Desktop (≥1024px), Tablet (768–1023px), Mobile (<768px).
- Navigasi mobile menggunakan hamburger menu.

---

## 8. Desain Database

### 8.1 Tabel `users`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED AUTO_INCREMENT | Primary Key |
| `nama` | VARCHAR(100) | Nama lengkap pengguna |
| `email` | VARCHAR(100) UNIQUE | Email untuk login |
| `password` | VARCHAR(255) | Hash bcrypt |
| `role` | ENUM('admin','petugas','dokter','pasien') | Peran pengguna |
| `is_active` | TINYINT(1) DEFAULT 1 | Status akun aktif |
| `created_at` | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | Waktu registrasi |

### 8.2 Tabel `patients`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED AUTO_INCREMENT | Primary Key |
| `nik` | VARCHAR(16) UNIQUE | Nomor Induk Kependudukan |
| `nama` | VARCHAR(100) | Nama lengkap pasien |
| `tanggal_lahir` | DATE | Tanggal lahir |
| `jenis_kelamin` | ENUM('L','P') | Laki-laki / Perempuan |
| `no_hp` | VARCHAR(15) | Nomor telepon |
| `alamat` | TEXT | Alamat lengkap |
| `user_id` | INT UNSIGNED | Foreign Key → `users.id` |

### 8.3 Tabel `poli`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED AUTO_INCREMENT | Primary Key |
| `nama_poli` | VARCHAR(100) | Nama poli |
| `kode_poli` | VARCHAR(5) UNIQUE | Kode prefix nomor antrean (A, G, K, dst.) |
| `avg_service_time` | INT DEFAULT 10 | Rata-rata menit per pasien |
| `is_active` | TINYINT(1) DEFAULT 1 | Status poli aktif |

### 8.4 Tabel `queues`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED AUTO_INCREMENT | Primary Key |
| `nomor_antrian` | VARCHAR(10) | Nomor antrean (misal: A001) |
| `pasien_id` | INT UNSIGNED | Foreign Key → `patients.id` |
| `poli_id` | INT UNSIGNED | Foreign Key → `poli.id` |
| `tanggal` | DATE | Tanggal antrean |
| `jam_daftar` | DATETIME | Waktu pengambilan antrean |
| `status` | ENUM('menunggu','dipanggil','dalam_pemeriksaan','selesai','dibatalkan','tidak_hadir') | Status antrean |
| `kategori_prioritas` | ENUM('normal','lansia','hamil','disabilitas','darurat') DEFAULT 'normal' | Prioritas |
| `estimasi_waktu` | DATETIME NULL | Estimasi waktu pelayanan |
| `created_at` | TIMESTAMP DEFAULT CURRENT_TIMESTAMP | Waktu record dibuat |

### 8.5 Tabel `service_records`

| Kolom | Tipe | Keterangan |
|---|---|---|
| `id` | INT UNSIGNED AUTO_INCREMENT | Primary Key |
| `queue_id` | INT UNSIGNED | Foreign Key → `queues.id` |
| `dokter_id` | INT UNSIGNED | Foreign Key → `users.id` (role dokter) |
| `waktu_mulai` | DATETIME | Waktu pemeriksaan dimulai |
| `waktu_selesai` | DATETIME NULL | Waktu pemeriksaan selesai |
| `catatan` | TEXT NULL | Catatan dokter |

### 8.6 Relasi Antar Tabel (ERD Ringkas)

```
users (1) ──────── (1) patients
  │
  └─── (1) ──── (M) service_records (sebagai dokter)

patients (1) ──── (M) queues
poli    (1) ──── (M) queues
queues  (1) ──── (1) service_records
```

---

## 9. Alur Sistem (System Flow)

### 9.1 Alur Registrasi Pasien

```
Mulai
  │
  ▼
Pasien membuka halaman registrasi
  │
  ▼
Pasien mengisi form (nama, NIK, tgl lahir, dsb.)
  │
  ▼
[Validasi Client-Side (JavaScript)]
  │
  ├─ Gagal ──► Tampilkan pesan error → Kembali ke form
  │
  ▼
[Submit ke server (PHP)]
  │
  ▼
[Validasi Server-Side]
  │
  ├─ NIK/Email sudah ada ──► Kembalikan error → Kembali ke form
  │
  ▼
Simpan ke tabel users + patients
  │
  ▼
Redirect ke halaman login
  │
Selesai
```

### 9.2 Alur Pengambilan Nomor Antrean

```
Mulai
  │
  ▼
Pasien login dan membuka halaman ambil antrean
  │
  ▼
Pasien memilih poli dan kategori prioritas
  │
  ▼
[Cek: pasien sudah punya antrean aktif hari ini?]
  │
  ├─ Ya ──► Tampilkan pesan "Anda sudah punya antrean aktif"
  │
  ▼
[Buka transaksi database]
  │
  ▼
Ambil nomor terakhir untuk poli + tanggal ini (SELECT FOR UPDATE)
  │
  ▼
Generate nomor baru (increment + format kode poli)
  │
  ▼
Hitung estimasi waktu tunggu
  │
  ▼
INSERT ke tabel queues
  │
  ▼
[Commit transaksi]
  │
  ▼
Tampilkan nomor antrean dan estimasi ke pasien
  │
Selesai
```

### 9.3 Alur Pemanggilan Pasien (Petugas)

```
Mulai
  │
  ▼
Petugas membuka dashboard
  │
  ▼
Petugas klik "Panggil Berikutnya" untuk poli tertentu
  │
  ▼
[Sistem query: antrean status 'menunggu' urut prioritas + jam_daftar]
  │
  ▼
Update status antrean → 'dipanggil'
  │
  ▼
Tampilkan nomor di layar monitoring (update via AJAX)
  │
  ▼
[Pasien hadir?]
  │
  ├─ Tidak ──► Petugas tandai "Tidak Hadir" → status = 'tidak_hadir'
  │              Panggil nomor berikutnya
  │
  ▼ Ya
Update status → 'dalam_pemeriksaan'
  │
  ▼
Dokter melayani pasien
  │
  ▼
Dokter klik "Selesai" → status = 'selesai'
  │
  ▼
INSERT ke tabel service_records
  │
Selesai
```

---

## 10. Desain UI/UX

### 10.1 Halaman & Komponen

| Halaman | Role | Komponen Utama |
|---|---|---|
| `/` (Monitoring Publik) | Publik | Card nomor dipanggil, card berikutnya, counter realtime |
| `/login.php` | Semua | Form login, validasi inline |
| `/register.php` | Publik | Form registrasi multi-field, validasi inline |
| `/pasien/dashboard.php` | Pasien | Card info antrean, tombol ambil antrean |
| `/pasien/ambil_antrian.php` | Pasien | Select poli, select prioritas, konfirmasi |
| `/pasien/riwayat.php` | Pasien | Tabel riwayat kunjungan dengan filter tanggal |
| `/petugas/dashboard.php` | Petugas | Card statistik, tabel antrean aktif per poli |
| `/petugas/panggil.php` | Petugas | Panel per poli, tombol panggil/ulang/tidak hadir |
| `/dokter/dashboard.php` | Dokter | Daftar pasien poli dokter, tombol selesai |
| `/admin/dashboard.php` | Admin | Card statistik, 3 grafik Chart.js |
| `/admin/users.php` | Admin | Tabel pengguna, modal tambah/edit |
| `/admin/poli.php` | Admin | Tabel poli, modal tambah/edit |

### 10.2 Komponen UI Reusable

- **Sidebar** — navigasi vertikal berbeda per role, collapsible di mobile.
- **Topbar/Navbar** — nama pengguna, avatar, tombol logout, notifikasi.
- **Card Statistik** — icon + label + nilai besar, warna berbeda per metrik.
- **Data Table** — header sticky, sorting, pencarian inline, pagination.
- **Modal Popup** — untuk form tambah/edit/konfirmasi aksi.
- **Alert/Toast** — notifikasi sukses (hijau), error (merah), warning (kuning) yang auto-dismiss.
- **Badge Status** — warna berbeda per status antrean (menunggu=biru, dipanggil=kuning, selesai=hijau, dsb.).

### 10.3 Palet Warna

| Elemen | Warna |
|---|---|
| Primary (Sidebar, Tombol Utama) | `#2C7BE5` (Biru) |
| Success | `#00D97E` (Hijau) |
| Warning | `#F6C343` (Kuning) |
| Danger | `#E63757` (Merah) |
| Background | `#F9FBFD` |
| Teks Utama | `#12263F` |

---

## 11. Struktur Folder Proyek

```
smart-queue-system/
│
├── config/
│   └── database.php           # Konfigurasi koneksi PDO MySQL
│
├── api/                        # Endpoint AJAX (return JSON)
│   ├── queue_status.php        # Status antrean realtime
│   ├── call_next.php           # Panggil pasien berikutnya
│   ├── update_status.php       # Update status antrean
│   └── get_stats.php           # Data statistik dashboard
│
├── auth/
│   ├── login.php
│   ├── logout.php
│   └── register.php
│
├── pasien/
│   ├── dashboard.php
│   ├── ambil_antrian.php
│   └── riwayat.php
│
├── petugas/
│   ├── dashboard.php
│   ├── antrian.php
│   └── pasien.php
│
├── dokter/
│   ├── dashboard.php
│   └── pelayanan.php
│
├── admin/
│   ├── dashboard.php
│   ├── users.php
│   └── poli.php
│
├── includes/                   # Helper PHP reusable
│   ├── header.php
│   ├── sidebar.php
│   ├── footer.php
│   ├── functions.php           # Fungsi generate nomor, estimasi, dsb.
│   └── auth_middleware.php     # Cek session dan role
│
├── assets/
│   ├── css/
│   │   ├── style.css           # Stylesheet utama
│   │   └── responsive.css      # Media queries
│   ├── js/
│   │   ├── main.js             # Script utama
│   │   ├── realtime.js         # AJAX polling monitoring
│   │   ├── validation.js       # Validasi form client-side
│   │   └── charts.js           # Inisialisasi Chart.js
│   └── img/
│       └── logo.png
│
├── database/
│   └── smart_queue.sql         # Script SQL lengkap (CREATE + seed data)
│
├── docs/
│   ├── PRD.md                  # Dokumen ini
│   ├── test_cases.md           # Dokumentasi test case
│   ├── erd.png                 # Entity Relationship Diagram
│   ├── use_case.png            # Use Case Diagram
│   ├── activity_diagram.png    # Activity Diagram
│   ├── flowchart.png           # Flowchart Sistem
│   └── installation_guide.md  # Panduan instalasi
│
└── index.php                   # Halaman monitoring publik / landing
```

---

## 12. Implementasi Pengujian Perangkat Lunak

### 12.1 Metode Pengujian

| Metode | Deskripsi | Target Modul |
|---|---|---|
| **Black Box Testing** | Uji fungsionalitas tanpa melihat kode internal | Semua modul |
| **Equivalence Partitioning** | Bagi input menjadi kelas valid dan tidak valid | Registrasi, Login, Antrean |
| **Boundary Value Analysis** | Uji nilai batas dari setiap input | NIK (16 digit), Password (8 karakter) |
| **User Acceptance Testing (UAT)** | Pengujian oleh pengguna akhir sesuai skenario nyata | Semua modul dari perspektif user |

### 12.2 Tabel Test Case (Minimal 30 Test Case)

#### Registrasi Pasien (TC-001 s.d. TC-007)

| TC ID | Nama Test Case | Input | Expected Output | Metode |
|---|---|---|---|---|
| TC-001 | Registrasi data valid | Semua field terisi benar | Akun berhasil dibuat, redirect ke login | Black Box |
| TC-002 | NIK kurang dari 16 digit | NIK = "123456789" (9 digit) | Pesan error "NIK harus 16 digit" | BVA |
| TC-003 | NIK lebih dari 16 digit | NIK = "12345678901234567" (17 digit) | Pesan error "NIK harus 16 digit" | BVA |
| TC-004 | NIK tepat 16 digit | NIK = "1234567890123456" | Diterima, lanjut validasi lain | BVA |
| TC-005 | Email sudah terdaftar | Email yang ada di database | Pesan error "Email sudah digunakan" | EP |
| TC-006 | Password kurang dari 8 karakter | Password = "abc123" (6 karakter) | Pesan error "Password minimal 8 karakter" | BVA |
| TC-007 | Password tidak sama | Password ≠ Konfirmasi | Pesan error "Password tidak cocok" | Black Box |

#### Login (TC-008 s.d. TC-012)

| TC ID | Nama Test Case | Input | Expected Output | Metode |
|---|---|---|---|---|
| TC-008 | Login berhasil sebagai pasien | Email & password valid (role pasien) | Redirect ke dashboard pasien | Black Box |
| TC-009 | Login berhasil sebagai petugas | Email & password valid (role petugas) | Redirect ke dashboard petugas | Black Box |
| TC-010 | Password salah | Email valid, password salah | Pesan error "Email atau password salah" | EP |
| TC-011 | Email tidak terdaftar | Email tidak ada di database | Pesan error "Email atau password salah" | EP |
| TC-012 | Field kosong | Email dan/atau password kosong | Pesan error validasi field wajib | Black Box |

#### Pengambilan Antrean (TC-013 s.d. TC-018)

| TC ID | Nama Test Case | Input | Expected Output | Metode |
|---|---|---|---|---|
| TC-013 | Ambil antrean berhasil | Poli dipilih, pasien login | Nomor antrean digenerate, tampil ke pasien | Black Box |
| TC-014 | Nomor antrean otomatis sequential | Antrean ke-2 di Poli Umum | Nomor = A002 (setelah A001) | Black Box |
| TC-015 | Cegah antrean ganda | Pasien yang sudah punya antrean aktif klik ambil lagi | Pesan error "Anda sudah memiliki antrean aktif" | Black Box |
| TC-016 | Poli tidak dipilih | Submit tanpa memilih poli | Pesan error "Pilih poli terlebih dahulu" | Black Box |
| TC-017 | Reset nomor tiap hari | Ambil antrean di hari berbeda | Nomor mulai dari 001 lagi | Black Box |
| TC-018 | Antrean prioritas darurat didahulukan | Ada pasien normal dan darurat | Pasien darurat dipanggil lebih dulu | Black Box |

#### Pemanggilan Pasien (TC-019 s.d. TC-023)

| TC ID | Nama Test Case | Input | Expected Output | Metode |
|---|---|---|---|---|
| TC-019 | Panggil berikutnya berurutan | Klik "Panggil Berikutnya" | Nomor terkecil dengan status menunggu dipanggil | Black Box |
| TC-020 | Tandai pasien tidak hadir | Klik "Tidak Hadir" pada nomor dipanggil | Status berubah ke 'tidak_hadir', sistem lanjut ke nomor berikutnya | Black Box |
| TC-021 | Panggil ulang pasien | Klik "Panggil Ulang" | Status tetap 'dipanggil', tampil di layar monitoring lagi | Black Box |
| TC-022 | Tandai selesai pemeriksaan | Dokter klik "Selesai" | Status = 'selesai', record disimpan di service_records | Black Box |
| TC-023 | Batalkan antrean yang sedang menunggu | Klik "Batalkan" | Status = 'dibatalkan' | Black Box |

#### Antrean Prioritas (TC-024 s.d. TC-027)

| TC ID | Nama Test Case | Input | Expected Output | Metode |
|---|---|---|---|---|
| TC-024 | Pasien lansia diprioritaskan | Pasien lansia daftar setelah pasien normal | Pasien lansia dipanggil sebelum pasien normal | Black Box |
| TC-025 | Pasien ibu hamil diprioritaskan | Pasien hamil daftar setelah pasien normal | Pasien hamil dipanggil sebelum pasien normal | Black Box |
| TC-026 | Pasien darurat didahulukan dari semua | Pasien darurat daftar terakhir | Pasien darurat dipanggil paling awal | Black Box |
| TC-027 | Pasien disabilitas diprioritaskan | Pasien disabilitas daftar setelah normal | Dipanggil sebelum pasien normal | Black Box |

#### Monitoring Realtime (TC-028 s.d. TC-030)

| TC ID | Nama Test Case | Input | Expected Output | Metode |
|---|---|---|---|---|
| TC-028 | Update data berhasil via AJAX | Petugas panggil nomor baru | Halaman monitoring memperbarui data dalam ≤ 5 detik tanpa refresh | Black Box |
| TC-029 | Halaman monitoring tanpa login | Akses `/index.php` tanpa session | Halaman monitoring tetap dapat diakses (publik) | Black Box |
| TC-030 | Data statistik dashboard akurat | Setelah beberapa transaksi | Angka di card statistik sesuai dengan data di database | Black Box |

### 12.3 Template Laporan Hasil Pengujian

```markdown
## Laporan Hasil Pengujian

| TC ID | Nama Test Case | Status | Tanggal Uji | Penguji | Catatan |
|-------|---------------|--------|-------------|---------|---------|
| TC-001 | Registrasi data valid | ✅ PASS | | | |
| TC-002 | NIK kurang 16 digit | ✅ PASS | | | |
| ...   | ...            | ...    | | | |

**Ringkasan:**
- Total test case   : 30
- PASS              : __
- FAIL              : __
- Persentase lulus  : __%
```

---

## 13. Kriteria Penerimaan (Acceptance Criteria)

Sistem dianggap siap untuk pengujian dan pengumpulan tugas apabila memenuhi seluruh kriteria berikut:

| # | Kriteria | Status |
|---|---|---|
| 1 | Semua halaman dapat diakses tanpa PHP/MySQL error | [ ] |
| 2 | Registrasi pasien berhasil menyimpan data ke database | [ ] |
| 3 | Login berhasil untuk semua role (pasien, petugas, dokter, admin) | [ ] |
| 4 | Nomor antrean digenerate tanpa duplikasi | [ ] |
| 5 | Status antrean berubah sesuai alur yang didefinisikan | [ ] |
| 6 | Halaman monitoring memperbarui data secara otomatis | [ ] |
| 7 | Antrean prioritas dipanggil sesuai urutan | [ ] |
| 8 | Dashboard admin menampilkan grafik Chart.js dengan data nyata | [ ] |
| 9 | Tampilan responsif di desktop, tablet, dan mobile | [ ] |
| 10 | Password disimpan dalam format hash (bukan plaintext) | [ ] |
| 11 | Semua input sudah disanitasi (proteksi XSS & SQL Injection) | [ ] |
| 12 | Minimal 30 test case terdokumentasi | [ ] |

---

## 14. Dokumentasi & Deliverable

| Deliverable | Format | Deskripsi |
|---|---|---|
| Source Code | PHP, JS, CSS, HTML | Semua file aplikasi |
| Database | `.sql` | Script CREATE TABLE + data seed |
| ERD | PNG/PDF | Entity Relationship Diagram |
| Use Case Diagram | PNG/PDF | Diagram use case semua aktor |
| Activity Diagram | PNG/PDF | Diagram aktivitas alur utama |
| Flowchart Sistem | PNG/PDF | Flowchart pendaftaran & pemanggilan |
| Wireframe UI | PNG/PDF | Sketsa tampilan halaman utama |
| Panduan Instalasi | Markdown/PDF | Langkah setup XAMPP + import database |
| Panduan Penggunaan | Markdown/PDF | Manual pengguna per role |
| Dokumentasi Pengujian | Markdown/PDF | Test case, hasil, analisis |

---

## 15. Asumsi & Batasan

### 15.1 Asumsi

- Sistem dijalankan di lingkungan XAMPP lokal (Windows/Linux/Mac).
- Rata-rata waktu pelayanan per pasien diasumsikan 10 menit sebagai nilai default.
- Setiap klinik hanya memiliki satu dokter aktif per poli dalam satu hari.
- Tanggal dan waktu sistem mengikuti jam server lokal (WIB diasumsikan UTC+7).
- Pasien hanya dapat memiliki satu antrean aktif per hari di seluruh poli.

### 15.2 Batasan

- Tidak ada integrasi dengan sistem eksternal (BPJS, SIMRS, dll.).
- Sistem tidak mengirim notifikasi ke perangkat pasien (SMS/Push/WhatsApp).
- Tidak ada fitur multi-klinik/multi-cabang.
- Tidak ada backup database otomatis dalam cakupan proyek ini.

---

## 16. Glosarium

| Istilah | Definisi |
|---|---|
| **Antrean Aktif** | Antrean dengan status `menunggu` atau `dipanggil` |
| **AJAX** | Asynchronous JavaScript and XML — teknik update data tanpa reload halaman |
| **BVA** | Boundary Value Analysis — pengujian pada nilai batas input |
| **EP** | Equivalence Partitioning — pembagian input ke kelas valid/tidak valid |
| **Nomor Antrean** | Kode unik per poli per hari (contoh: A001, G003) |
| **Poli** | Unit pelayanan medis (Poli Umum, Poli Gigi, dll.) |
| **Prioritas** | Kategori pasien yang mendapat giliran lebih awal |
| **Role** | Peran pengguna dalam sistem (admin, petugas, dokter, pasien) |
| **Session** | Mekanisme PHP untuk menyimpan status login pengguna |
| **UAT** | User Acceptance Testing — pengujian oleh pengguna akhir |
| **XAMPP** | Paket web server lokal (Apache + MySQL + PHP) |

---

*Dokumen ini merupakan bagian dari proyek tugas mata kuliah Implementasi Pengujian Perangkat Lunak. Versi berikutnya akan diperbarui sesuai perkembangan implementasi.*
