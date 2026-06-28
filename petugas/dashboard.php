<?php
/**
 * Dashboard Petugas
 * Halaman utama petugas untuk melihat statistik dan mengelola antrean
 * 
 * @package Smart Queue System
 * @subpackage Petugas
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['petugas', 'admin']);

$stats = $pdo->query("SELECT COUNT(*) total_today, SUM(status='menunggu') waiting_today,
    SUM(status='dipanggil') called_today, SUM(status='selesai') completed_today,
    SUM(status='dibatalkan') cancelled_today, SUM(status='tidak_hadir') no_show_today
    FROM queues WHERE tanggal=CURDATE()")->fetch();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Dashboard Petugas</h1>
                
                <div class="stats-row">
                    <div class="stat-card">
                        <h3>Jumlah Antrean Hari Ini</h3>
                        <p class="stat-value" data-dashboard-stat="total_today"><?php echo (int) $stats['total_today']; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Sedang Dipanggil</h3>
                        <p class="stat-value" data-dashboard-stat="called_today"><?php echo (int) $stats['called_today']; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Menunggu</h3>
                        <p class="stat-value" data-dashboard-stat="waiting_today"><?php echo (int) $stats['waiting_today']; ?></p>
                    </div>
                </div>
                
                <div class="stats-row">
                    <div class="stat-card">
                        <h3>Selesai</h3>
                        <p class="stat-value" data-dashboard-stat="completed_today"><?php echo (int) $stats['completed_today']; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Tidak Hadir</h3>
                        <p class="stat-value" data-dashboard-stat="no_show_today"><?php echo (int) $stats['no_show_today']; ?></p>
                    </div>
                    <div class="stat-card"><h3>Dibatalkan</h3><p class="stat-value" data-dashboard-stat="cancelled_today"><?php echo (int) $stats['cancelled_today']; ?></p></div>
                    <div class="stat-card"><h3>Estimasi Tunggu</h3><p class="stat-value"><span data-dashboard-stat="estimated_wait_minutes">0</span> menit</p></div>
                </div>
                
                <div class="card">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="antrian.php">Kelola Antrean</a></li>
                        <li><a href="pasien.php">Data Pasien</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
