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

// Ambil statistik
$stmt = $pdo->query('SELECT COUNT(*) as total FROM patients');
$total_patients = $stmt->fetch()['total'];

$stmt = $pdo->query('SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE()');
$total_today = $stmt->fetch()['total'];

$stmt = $pdo->query('SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE() AND status = "selesai"');
$completed_today = $stmt->fetch()['total'];

$stmt = $pdo->query('SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE() AND status = "dibatalkan"');
$cancelled_today = $stmt->fetch()['total'];

$stmt = $pdo->query('SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE() AND status = "tidak_hadir"');
$no_show_today = $stmt->fetch()['total'];

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
                        <h3>Total Pasien Terdaftar</h3>
                        <p class="stat-value"><?php echo $total_patients; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Total Antrean Hari Ini</h3>
                        <p class="stat-value"><?php echo $total_today; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Pasien Selesai</h3>
                        <p class="stat-value"><?php echo $completed_today; ?></p>
                    </div>
                </div>
                
                <div class="stats-row">
                    <div class="stat-card">
                        <h3>Pembatalan</h3>
                        <p class="stat-value"><?php echo $cancelled_today; ?></p>
                    </div>
                    
                    <div class="stat-card">
                        <h3>Tidak Hadir</h3>
                        <p class="stat-value"><?php echo $no_show_today; ?></p>
                    </div>
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
</body>
</html>
