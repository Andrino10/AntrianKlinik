<?php
/**
 * Kelola Poli (Admin)
 * Halaman untuk admin mengelola data poli
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

// Ambil daftar poli
$stmt = $pdo->query('SELECT * FROM poli ORDER BY nama_poli ASC');
$poli_list = $stmt->fetchAll();

// Handle tambah/edit poli
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $nama_poli = htmlspecialchars($_POST['nama_poli'] ?? '');
    $kode_poli = htmlspecialchars($_POST['kode_poli'] ?? '');
    $avg_service_time = $_POST['avg_service_time'] ?? 10;
    $is_active = $_POST['is_active'] ?? 1;
    
    if ($action === 'add') {
        if (empty($nama_poli) || empty($kode_poli)) {
            $error = 'Nama poli dan kode poli harus diisi';
        } else {
            try {
                $stmt = $pdo->prepare('
                    INSERT INTO poli (nama_poli, kode_poli, avg_service_time, is_active) 
                    VALUES (?, ?, ?, ?)
                ');
                $stmt->execute([$nama_poli, $kode_poli, $avg_service_time, $is_active]);
                $success = 'Poli berhasil ditambahkan';
            } catch (Exception $e) {
                $error = 'Error: ' . $e->getMessage();
            }
        }
    } elseif ($action === 'edit') {
        $poli_id = $_POST['poli_id'] ?? null;
        
        if (!$poli_id) {
            $error = 'Poli ID tidak diberikan';
        } else {
            try {
                $stmt = $pdo->prepare('
                    UPDATE poli 
                    SET nama_poli = ?, kode_poli = ?, avg_service_time = ?, is_active = ? 
                    WHERE id = ?
                ');
                $stmt->execute([$nama_poli, $kode_poli, $avg_service_time, $is_active, $poli_id]);
                $success = 'Poli berhasil diperbarui';
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
    <title>Kelola Poli - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Kelola Poli</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <button class="btn btn-primary" onclick="openAddModal()">Tambah Poli</button>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama Poli</th>
                                <th>Kode Poli</th>
                                <th>Avg Service Time (menit)</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($poli_list as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['nama_poli']); ?></td>
                                    <td><?php echo htmlspecialchars($p['kode_poli']); ?></td>
                                    <td><?php echo $p['avg_service_time']; ?></td>
                                    <td>
                                        <span class="badge <?php echo $p['is_active'] ? 'badge-success' : 'badge-danger'; ?>">
                                            <?php echo $p['is_active'] ? 'Aktif' : 'Nonaktif'; ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-info" onclick="openEditModal(<?php echo $p['id']; ?>)">Edit</button>
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
            alert('Modal form untuk tambah poli');
        }
        
        function openEditModal(poliId) {
            alert('Modal form untuk edit poli ID: ' + poliId);
        }
    </script>
</body>
</html>
