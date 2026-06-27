<?php
/**
 * Kelola Antrean Petugas
 * Halaman untuk petugas mengelola pemanggilan pasien
 * 
 * @package Smart Queue System
 * @subpackage Petugas
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['petugas', 'admin']);

// Ambil daftar poli
$stmt = $pdo->query('SELECT * FROM poli WHERE is_active = 1');
$poli_list = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Antrean - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Kelola Antrean</h1>
                
                <div class="poli-tabs">
                    <?php foreach ($poli_list as $p): ?>
                        <div class="poli-panel" data-poli-id="<?php echo $p['id']; ?>">
                            <h3><?php echo htmlspecialchars($p['nama_poli']); ?></h3>
                            <div class="queue-controls">
                                <button class="btn btn-primary btn-call-next">Panggil Berikutnya</button>
                                <button class="btn btn-warning btn-call-again">Panggil Ulang</button>
                                <button class="btn btn-danger btn-no-show">Tidak Hadir</button>
                            </div>
                            
                            <div class="queue-list">
                                <h4>Antrean Aktif</h4>
                                <ul id="queue-<?php echo $p['id']; ?>">
                                    <li>Loading...</li>
                                </ul>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/realtime.js"></script>
</body>
</html>
