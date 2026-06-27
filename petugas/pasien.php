<?php
/**
 * Data Pasien (Petugas)
 * Halaman untuk melihat dan mengelola data pasien
 * 
 * @package Smart Queue System
 * @subpackage Petugas
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['petugas', 'admin']);

// Ambil daftar pasien
$stmt = $pdo->query('
    SELECT p.*, u.email, u.is_active 
    FROM patients p 
    JOIN users u ON p.user_id = u.id 
    ORDER BY p.nama ASC
');
$patients = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pasien - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Data Pasien</h1>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Email</th>
                                <th>No. Telepon</th>
                                <th>Alamat</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nik']); ?></td>
                                    <td><?php echo htmlspecialchars($p['email']); ?></td>
                                    <td><?php echo htmlspecialchars($p['no_hp']); ?></td>
                                    <td><?php echo htmlspecialchars($p['alamat']); ?></td>
                                    <td>
                                        <span class="badge <?php echo $p['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $p['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                        </span>
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
</body>
</html>
