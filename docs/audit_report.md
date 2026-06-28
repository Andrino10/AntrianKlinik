# Audit Smart Queue System

Tanggal audit: 28 Juni 2026

## Ringkasan

Backend pengelolaan antrean petugas telah dilengkapi: daftar pasien aktif dari database, enam aksi antrean, polling 5 detik tanpa refresh, statistik dashboard, CSRF untuk API mutasi, validasi transisi status, dan transaction/row locking saat memanggil antrean.

Tidak ada perubahan tabel atau nama database. Kontrak field lama API dipertahankan; response hanya ditambah field yang dibutuhkan frontend baru.

## Temuan dan status

### Diperbaiki

- Kritis: dua fungsi `updateQueueStatus` saling menimpa dan polling mengirim POST dengan parameter `undefined`.
- Kritis: `call_next.php` dapat memanggil pasien yang sama pada request bersamaan. Sekarang menggunakan transaksi dan `FOR UPDATE`.
- Tinggi: endpoint mutasi tidak memiliki CSRF. `call_next.php` dan `update_status.php` sekarang memverifikasi token.
- Tinggi: status dapat diubah ke sembarang state. Sekarang hanya transisi workflow yang sah yang diterima.
- Tinggi: API auth sebelumnya dapat merespons redirect HTML. Helper API sekarang memberi JSON dan HTTP status 401/403/405/419/422/409/500.
- Tinggi: detail error koneksi database terekspos. Sekarang dicatat ke error log dan pengguna mendapat pesan generik.
- Sedang: dashboard menjalankan banyak query COUNT. Digabung menjadi conditional aggregation.
- Sedang: polling dapat overlap dan tetap aktif saat tab tersembunyi. Sekarang request lama dibatalkan dan polling berhenti sementara.
- Sedang: halaman antrean adalah template tanpa data pasien dan handler aksi. Sekarang seluruh data dan aksi berasal dari database/API.
- Sedang: monitoring publik tidak pernah mengisi nomor berikutnya. Sekarang menggunakan `next_number`.
- Rendah: login tidak meregenerasi session ID. Sekarang memanggil `session_regenerate_id(true)`.

### Masih memerlukan keputusan/layanan tambahan

- Kritis: kredensial database pernah masuk repository. `.gitignore` tidak menghapus secret yang sudah ter-commit. Password database hosting harus segera dirotasi dan file konfigurasi deployment dikeluarkan dari riwayat Git.
- Kritis: `forgot_password.php` mengizinkan reset hanya dengan nama dan email. Solusi aman memerlukan token sekali pakai, masa berlaku, dan layanan email; sebaiknya endpoint dinonaktifkan sampai mekanisme itu tersedia.
- Tinggi: form POST lama di register, admin, pasien, dan dokter belum seluruhnya menggunakan CSRF.
- Tinggi: klaim kategori `darurat/hamil/disabilitas` dapat dipilih sendiri oleh pasien tanpa verifikasi petugas.
- Tinggi: pembuatan nomor antrean pasien belum memakai lock/unique constraint gabungan; submit paralel berpotensi membuat nomor sama.
- Sedang: halaman admin pengguna/poli menampilkan modal dummy sehingga tombol tambah/edit belum benar-benar membuka form.
- Sedang: pelayanan dokter menerima ID database di field berlabel “Nomor Antrean” dan pencatatan service record belum memiliki workflow mulai/selesai yang konsisten.
- Sedang: logout masih berupa GET dan belum dilindungi CSRF.
- Sedang: `get_base_url()` mempercayai `HTTP_HOST` serta mengasumsikan satu subfolder; konfigurasi base URL eksplisit lebih aman untuk hosting.
- Rendah: `assets/js/validation.js` tidak pernah dimuat dan menduplikasi `validateEmail`/`validateNIK` dari `main.js`.
- Rendah: helper `makeAjaxRequest`, `formatCurrency`, `formatDateId`, `disableFormOnSubmit`, `sanitize_input`, `is_ajax_request`, dan `log_activity` tidak digunakan.
- Rendah: beberapa teks lama mengalami mojibake (`â‰¥`, emoji rusak); encoding file perlu dinormalisasi UTF-8.

## Perubahan utama: sebelum dan sesudah

### `assets/js/realtime.js`

Sebelum:

```js
setInterval(updateQueueStatus, 5000);
updateQueueStatus();
function updateQueueStatus(queueId, newStatus) { /* POST */ }
```

Sesudah:

```js
refreshQueueList();
setInterval(refreshQueueList, 5000);
document.addEventListener('click', handleQueueClick);
```

Polling hanya melakukan GET daftar antrean. POST hanya dibuat dari klik yang terkonfirmasi.

### `api/call_next.php`

Sebelum: SELECT pasien lalu UPDATE tanpa transaksi.

Sesudah: validasi method/auth/CSRF, lock poli dan antrean aktif, pilih pasien berdasarkan prioritas dengan `FOR UPDATE`, lalu update dalam satu transaksi.

### `api/update_status.php`

Sebelum: menerima semua status ENUM untuk semua kondisi.

Sesudah: memvalidasi ID, status, antrean hari ini, dan state transition di dalam transaksi.

### `petugas/antrian.php`

Sebelum: hanya panel poli dan teks `Loading...`.

Sesudah: container realtime per poli, ringkasan status, dan tabel yang menampilkan nomor, nama, NIK, HP, prioritas, waktu daftar, estimasi, status, serta tombol kontekstual.

### `api/get_queue_list.php`

File baru. Mengembalikan antrean aktif dan summary hari ini menggunakan join terparameterisasi.

### `petugas/dashboard.php` dan `assets/js/dashboard.js`

Sebelum: angka statis dari lima query terpisah.

Sesudah: statistik lengkap dan polling satu endpoint setiap lima detik.

## Pengujian

1. Login sebagai petugas dan buka `petugas/antrian.php`.
2. Network pada load harus hanya berisi GET `get_queue_list.php`; tidak boleh ada POST.
3. Klik Panggil Berikutnya; pastikan satu antrean berubah dari `menunggu` ke `dipanggil` tanpa reload.
4. Uji Panggil Ulang, Tidak Hadir, Mulai Pemeriksaan, Selesai, dan Batalkan.
5. Buka halaman publik pada tab lain dan pastikan nomor berubah maksimal lima detik setelah aksi.
6. Buka dua session petugas dan klik Panggil Berikutnya bersamaan; hanya satu request boleh berhasil untuk poli yang sama.
7. Kirim POST tanpa `X-CSRF-Token`; API harus merespons HTTP 419.
8. Seluruh file PHP telah diperiksa dengan `php -l`, seluruh JS dengan `node --check`, dan `git diff --check`.

Integration test database hosting dari lingkungan audit gagal karena DNS `sql301.infinityfree.com` tidak dapat di-resolve. Karena itu transaksi/query perlu smoke test sekali lagi langsung di hosting.
