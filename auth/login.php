<?php
/**
 * Halaman Login
 * Form login untuk semua pengguna (pasien, petugas, dokter, admin)
 * 
 * @package Smart Queue System
 * @subpackage Auth
 */

session_start();

// Jika sudah login, redirect ke dashboard sesuai role
if (isset($_SESSION['user_id'])) {
    $role = $_SESSION['role'];
    $dashboard_urls = [
        'pasien' => 'pasien/dashboard.php',
        'petugas' => 'petugas/dashboard.php',
        'dokter' => 'dokter/dashboard.php',
        'admin' => 'admin/dashboard.php'
    ];
    header('Location: ../' . ($dashboard_urls[$role] ?? 'index.php'));
    exit;
}

require_once '../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($email) || empty($password)) {
        $error = 'Email dan password harus diisi';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? AND is_active = 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Login berhasil
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['nama'] = $user['nama'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];
                
                // Redirect ke dashboard sesuai role
                $role = $user['role'];
                $dashboard_urls = [
                    'pasien' => 'pasien/dashboard.php',
                    'petugas' => 'petugas/dashboard.php',
                    'dokter' => 'dokter/dashboard.php',
                    'admin' => 'admin/dashboard.php'
                ];
                header('Location: ../' . ($dashboard_urls[$role] ?? 'index.php'));
                exit;
            } else {
                $error = 'Email atau password salah';
            }
        } catch (Exception $e) {
            $error = 'Terjadi kesalahan: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Smart Queue System</h1>
            <p>Sistem Antrean Digital Klinik/Puskesmas</p>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" action="">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required class="form-control">
                </div>
                
                <button type="submit" class="btn btn-primary btn-block">Login</button>
            </form>

            <a href="forgot_password.php" class="btn btn-secondary btn-block mt-3">Lupa Password</a>
            
            <p class="text-center mt-3">
                Belum punya akun? <a href="register.php">Daftar di sini</a>
            </p>
        </div>
    </div>
</body>
</html>
