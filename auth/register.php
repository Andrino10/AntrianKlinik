<?php
/**
 * Halaman Registrasi Pasien
 * Form pendaftaran pasien baru ke sistem
 * 
 * @package Smart Queue System
 * @subpackage Auth
 */

session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars($_POST['nama'] ?? '');
    $nik = htmlspecialchars($_POST['nik'] ?? '');
    $tanggal_lahir = $_POST['tanggal_lahir'] ?? '';
    $jenis_kelamin = htmlspecialchars($_POST['jenis_kelamin'] ?? '');
    $no_hp = htmlspecialchars($_POST['no_hp'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';
    $alamat = htmlspecialchars($_POST['alamat'] ?? '');
    
    // Validasi
    if (empty($nama) || strlen($nama) < 3) {
        $error = 'Nama harus minimal 3 karakter';
    } elseif (empty($nik) || strlen($nik) !== 16 || !is_numeric($nik)) {
        $error = 'NIK harus 16 digit angka';
    } elseif (empty($tanggal_lahir) || strtotime($tanggal_lahir) > time()) {
        $error = 'Tanggal lahir tidak valid';
    } elseif (empty($jenis_kelamin)) {
        $error = 'Jenis kelamin harus dipilih';
    } elseif (empty($no_hp)) {
        $error = 'Nomor telepon harus diisi';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid';
    } elseif (strlen($password) < 8) {
        $error = 'Password minimal 8 karakter';
    } elseif ($password !== $password_confirm) {
        $error = 'Password tidak cocok';
    } elseif (empty($alamat)) {
        $error = 'Alamat harus diisi';
    } else {
        try {
            // Cek NIK dan Email sudah ada
            $stmt = $pdo->prepare('SELECT id FROM patients WHERE nik = ?');
            $stmt->execute([$nik]);
            if ($stmt->fetch()) {
                throw new Exception('NIK sudah terdaftar');
            }
            
            $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetch()) {
                throw new Exception('Email sudah digunakan');
            }
            
            // Hash password
            $password_hash = password_hash($password, PASSWORD_BCRYPT);
            
            // Mulai transaksi
            $pdo->beginTransaction();
            
            // Insert ke tabel users
            $stmt = $pdo->prepare('
                INSERT INTO users (nama, email, password, role, is_active, created_at) 
                VALUES (?, ?, ?, ?, 1, NOW())
            ');
            $stmt->execute([$nama, $email, $password_hash, 'pasien']);
            $user_id = $pdo->lastInsertId();
            
            // Insert ke tabel patients
            $stmt = $pdo->prepare('
                INSERT INTO patients (nik, nama, tanggal_lahir, jenis_kelamin, no_hp, alamat, user_id) 
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ');
            $stmt->execute([$nik, $nama, $tanggal_lahir, $jenis_kelamin, $no_hp, $alamat, $user_id]);
            
            // Commit transaksi
            $pdo->commit();
            
            $success = 'Pendaftaran berhasil! Silahkan login.';
            
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
            if ($pdo->inTransaction()) {
                $pdo->rollBack();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="register-page">
    <div class="register-container">
        <div class="register-box">
            <h1>Daftar Akun</h1>
            <p>Smart Queue System - Klinik/Puskesmas</p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p class="text-center"><a href="login.php" class="btn btn-primary">Ke Halaman Login</a></p>
            <?php else: ?>
                <form method="POST" action="" id="registerForm">
                    <div class="form-group">
                        <label for="nama">Nama Lengkap</label>
                        <input type="text" id="nama" name="nama" required class="form-control">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nik">NIK (16 digit)</label>
                            <input type="text" id="nik" name="nik" maxlength="16" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" id="tanggal_lahir" name="tanggal_lahir" required class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select id="jenis_kelamin" name="jenis_kelamin" required class="form-control">
                                <option value="">Pilih...</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="no_hp">Nomor Telepon</label>
                            <input type="tel" id="no_hp" name="no_hp" required class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" required class="form-control">
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password (minimal 8 karakter)</label>
                            <input type="password" id="password" name="password" minlength="8" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="password_confirm">Konfirmasi Password</label>
                            <input type="password" id="password_confirm" name="password_confirm" minlength="8" required class="form-control">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea id="alamat" name="alamat" required class="form-control"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Daftar</button>
                </form>
                
                <p class="text-center mt-3">
                    Sudah punya akun? <a href="login.php">Login di sini</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
