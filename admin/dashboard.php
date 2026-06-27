<?php
/**
 * Dashboard Admin
 * Halaman utama admin dengan statistik dan grafik
 * 
 * @package Smart Queue System
 * @subpackage Admin
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['admin']);

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

// Data untuk grafik: antrean per poli hari ini
$stmt = $pdo->query('
    SELECT p.nama_poli, COUNT(q.id) as total 
    FROM queues q 
    JOIN poli p ON q.poli_id = p.id 
    WHERE q.tanggal = CURDATE() 
    GROUP BY q.poli_id, p.nama_poli
');
$queue_per_poli = $stmt->fetchAll();

// Data untuk grafik: tren 7 hari terakhir
$stmt = $pdo->query('
    SELECT tanggal, COUNT(*) as total 
    FROM queues 
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
    GROUP BY tanggal 
    ORDER BY tanggal ASC
');
$trend_7days = $stmt->fetchAll();

// Data untuk grafik: distribusi status hari ini
$stmt = $pdo->query('
    SELECT status, COUNT(*) as total 
    FROM queues 
    WHERE tanggal = CURDATE() 
    GROUP BY status
');
$status_distribution = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Dashboard Admin</h1>
                
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
                
                <div class="charts-row">
                    <div class="chart-container">
                        <h3>Antrean per Poli (Hari Ini)</h3>
                        <canvas id="poliChart"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <h3>Tren Antrean 7 Hari Terakhir</h3>
                        <canvas id="trendChart"></canvas>
                    </div>
                    
                    <div class="chart-container">
                        <h3>Distribusi Status Antrean</h3>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>
                
                <div class="card">
                    <h3>Menu Admin</h3>
                    <ul>
                        <li><a href="users.php">Kelola Pengguna</a></li>
                        <li><a href="poli.php">Kelola Poli</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
    <script src="../assets/js/charts.js"></script>
    <script>
        // Data untuk chart poli
        const poliLabels = <?php echo json_encode(array_column($queue_per_poli, 'nama_poli')); ?>;
        const poliData = <?php echo json_encode(array_column($queue_per_poli, 'total')); ?>;
        
        // Data untuk trend 7 hari
        const trendLabels = <?php echo json_encode(array_column($trend_7days, 'tanggal')); ?>;
        const trendData = <?php echo json_encode(array_column($trend_7days, 'total')); ?>;
        
        // Data untuk status
        const statusLabels = <?php echo json_encode(array_column($status_distribution, 'status')); ?>;
        const statusData = <?php echo json_encode(array_column($status_distribution, 'total')); ?>;
        
        // Initialize charts
        initializeCharts(poliLabels, poliData, trendLabels, trendData, statusLabels, statusData);
    </script>
</body>
</html>
