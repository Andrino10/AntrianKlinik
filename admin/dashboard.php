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

// Gabungkan query statistik antrean hari ini dalam satu query (kondisional)
$stats_stmt = $pdo->query("
    SELECT 
        COUNT(*) as total_today,
        SUM(status = 'selesai') as completed_today,
        SUM(status = 'dibatalkan') as cancelled_today,
        SUM(status = 'tidak_hadir') as no_show_today
    FROM queues 
    WHERE tanggal = CURDATE()
");
$stats_today = $stats_stmt->fetch();

$total_today = (int) ($stats_today['total_today'] ?? 0);
$completed_today = (int) ($stats_today['completed_today'] ?? 0);
$cancelled_today = (int) ($stats_today['cancelled_today'] ?? 0);
$no_show_today = (int) ($stats_today['no_show_today'] ?? 0);

// Data untuk grafik 1: antrean per poli hari ini (menampilkan semua poli aktif, termasuk yang kosong / 0)
$stmt = $pdo->query('
    SELECT p.nama_poli, COUNT(q.id) as total 
    FROM poli p
    LEFT JOIN queues q ON p.id = q.poli_id AND q.tanggal = CURDATE() 
    WHERE p.is_active = 1
    GROUP BY p.id, p.nama_poli
');
$queue_per_poli = $stmt->fetchAll();

// Data untuk grafik 2: tren 7 hari terakhir (memastikan tidak ada gap tanggal kosong)
$dates = [];
for ($i = 6; $i >= 0; $i--) {
    $d = date('Y-m-d', strtotime("-$i days"));
    $dates[$d] = 0;
}
$stmt = $pdo->query('
    SELECT tanggal, COUNT(*) as total 
    FROM queues 
    WHERE tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY) 
    GROUP BY tanggal
');
while ($row = $stmt->fetch()) {
    $dates[$row['tanggal']] = (int) $row['total'];
}
$trend_labels = [];
$trend_data = [];
foreach ($dates as $tgl => $tot) {
    $trend_labels[] = date('d/m', strtotime($tgl)); // Tampilan format tanggal ringkas (dd/mm)
    $trend_data[] = $tot;
}

// Data untuk grafik 3: distribusi status antrean hari ini (memastikan semua status terpetakan)
$statuses = [
    'menunggu' => 0,
    'dipanggil' => 0,
    'dalam_pemeriksaan' => 0,
    'selesai' => 0,
    'tidak_hadir' => 0,
    'dibatalkan' => 0
];
$stmt = $pdo->query('
    SELECT status, COUNT(*) as total 
    FROM queues 
    WHERE tanggal = CURDATE() 
    GROUP BY status
');
while ($row = $stmt->fetch()) {
    if (isset($statuses[$row['status']])) {
        $statuses[$row['status']] = (int) $row['total'];
    }
}
$status_labels = array_keys($statuses);
$status_data = array_values($statuses);

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
        const trendLabels = <?php echo json_encode($trend_labels); ?>;
        const trendData = <?php echo json_encode($trend_data); ?>;
        
        // Data untuk status
        const statusLabels = <?php echo json_encode($status_labels); ?>;
        const statusData = <?php echo json_encode($status_data); ?>;
        
        // Initialize charts
        initializeCharts(poliLabels, poliData, trendLabels, trendData, statusLabels, statusData);
    </script>
</body>
</html>
