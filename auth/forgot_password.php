<?php
/**
 * Halaman Lupa Password
 * Reset password berdasarkan nama pengguna dan email terdaftar
 *
 * @package Smart Queue System
 * @subpackage Auth
 */

session_start();

if (isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

require_once '../config/database.php';

$error = '';
$success = '';
$nama = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim(htmlspecialchars($_POST['nama'] ?? ''));
    $email = trim(htmlspecialchars($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($nama) || strlen($nama) < 3) {
        $error = 'Nama pengguna harus diisi minimal 3 karakter';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email tidak valid';
    } elseif (strlen($password) < 8) {
        $error = 'Password baru minimal 8 karakter';
    } elseif ($password !== $password_confirm) {
        $error = 'Konfirmasi password tidak cocok';
    } else {
        try {
            $stmt = $pdo->prepare('SELECT id FROM users WHERE nama = ? AND email = ? AND is_active = 1');
            $stmt->execute([$nama, $email]);
            $user = $stmt->fetch();

            if (!$user) {
                $error = 'Nama pengguna dan email tidak cocok dengan akun terdaftar';
            } else {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);

                $stmt = $pdo->prepare('UPDATE users SET password = ? WHERE id = ?');
                $stmt->execute([$password_hash, $user['id']]);

                $success = 'Password berhasil diubah. Silahkan login dengan password baru.';
                $nama = '';
                $email = '';
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
    <title>Lupa Password - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-container">
        <div class="login-box">
            <h1>Lupa Password</h1>
            <p>Masukkan nama pengguna dan email yang terdaftar</p>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
                <p class="text-center"><a href="login.php" class="btn btn-primary">Ke Halaman Login</a></p>
            <?php else: ?>
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="nama">Nama Pengguna</label>
                        <input type="text" id="nama" name="nama" required class="form-control" value="<?php echo htmlspecialchars($nama); ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Terdaftar</label>
                        <input type="email" id="email" name="email" required class="form-control" value="<?php echo htmlspecialchars($email); ?>">
                    </div>

                    <div class="form-group">
                        <label for="password">Password Baru</label>
                        <input type="password" id="password" name="password" minlength="8" required class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="password_confirm">Konfirmasi Password Baru</label>
                        <input type="password" id="password_confirm" name="password_confirm" minlength="8" required class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary btn-block">Ubah Password</button>
                </form>

                <p class="text-center mt-3">
                    Ingat password? <a href="login.php">Login di sini</a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
