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

// Data antrean dimuat dari API agar dapat diperbarui tanpa refresh halaman.
$stmt = $pdo->query('SELECT id, nama_poli FROM poli WHERE is_active=1 ORDER BY nama_poli');
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

                <div class="stats-row queue-summary">
                    <div class="stat-card"><h3>Total Hari Ini</h3><p class="stat-value" data-stat="total">0</p></div>
                    <div class="stat-card"><h3>Menunggu</h3><p class="stat-value" data-stat="menunggu">0</p></div>
                    <div class="stat-card"><h3>Dipanggil</h3><p class="stat-value" data-stat="dipanggil">0</p></div>
                    <div class="stat-card"><h3>Dalam Pemeriksaan</h3><p class="stat-value" data-stat="dalam_pemeriksaan">0</p></div>
                </div>
                
                <div class="poli-tabs">
                    <?php foreach ($poli_list as $p): ?>
                        <div class="poli-panel" data-poli-id="<?php echo (int) $p['id']; ?>">
                            <h3><?php echo htmlspecialchars($p['nama_poli']); ?></h3>
                            <div class="queue-controls">
                                <button type="button" class="btn btn-primary btn-call-next">Panggil Berikutnya</button>
                            </div>
                            
                            <div class="queue-list">
                                <h4>Antrean Aktif</h4>
                                <div id="queue-<?php echo (int) $p['id']; ?>" class="queue-table-container">
                                    <p class="queue-empty">Memuat antrean...</p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <p class="update-time">Terakhir diperbarui: <span id="update-timestamp">--:--:--</span></p>
                
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/realtime.js"></script>
</body>
</html>
