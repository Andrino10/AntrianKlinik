<?php
/**
 * Kelola Pengguna (Admin)
 * Halaman untuk admin mengelola pengguna sistem
 * 
 * @package Smart Queue System
 * @subpackage Admin
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['admin']);

$error = '';
$success = '';

// Ambil daftar pengguna
$stmt = $pdo->query('SELECT * FROM users ORDER BY created_at DESC');
$users = $stmt->fetchAll();

// Handle tambah/edit pengguna
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nama = htmlspecialchars($_POST['nama'] ?? '');
    $email = htmlspecialchars($_POST['email'] ?? '');
    $role = htmlspecialchars($_POST['role'] ?? '');
    $is_active = $_POST['is_active'] ?? 1;
    
    if ($action === 'add') {
        $password = $_POST['password'] ?? '';
        
        if (empty($nama) || empty($email) || empty($role) || empty($password)) {
            $error = 'Semua field harus diisi';
        } else {
            try {
                $password_hash = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare('
                    INSERT INTO users (nama, email, password, role, is_active, created_at) 
                    VALUES (?, ?, ?, ?, ?, NOW())
                ');
                $stmt->execute([$nama, $email, $password_hash, $role, $is_active]);
                $success = 'Pengguna berhasil ditambahkan';
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'edit') {
        $user_id = $_POST['user_id'] ?? null;
        
        if (!$user_id) {
            $error = 'User ID tidak diberikan';
        } else {
            try {
                $stmt = $pdo->prepare('UPDATE users SET nama = ?, email = ?, role = ?, is_active = ? WHERE id = ?');
                $stmt->execute([$nama, $email, $role, $is_active, $user_id]);
                $success = 'Pengguna berhasil diperbarui';
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
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
    <title>Kelola Pengguna - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Kelola Pengguna</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <button class="btn btn-primary" onclick="openAddModal()">Tambah Pengguna</button>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $u): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($u['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($u['email']); ?></td>
                                    <td><?php echo ucfirst($u['role']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $u['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $u['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="openEditModal(<?php echo $u['id']; ?>)">Edit</button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
    
    <script>
        function openAddModal() {
            alert('Modal form untuk tambah pengguna');
        }
        
        function openEditModal(userId) {
            alert('Modal form untuk edit pengguna ID: ' + userId);
        }
    </script>
</body>
</html>
