<?php
/**
 * Dashboard Pasien
 * Halaman utama pasien untuk melihat status antrean
 * 
 * @package Smart Queue System
 * @subpackage Pasien
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['pasien']);

$user_id = $_SESSION['user_id'];

// Ambil data pasien
$stmt = $pdo->prepare('
    SELECT p.*, u.email 
    FROM patients p 
    JOIN users u ON p.user_id = u.id 
    WHERE u.id = ?
');
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

// Ambil antrean aktif hari ini
$stmt = $pdo->prepare('
    SELECT q.*, p.nama_poli, p.kode_poli 
    FROM queues q 
    JOIN poli p ON q.poli_id = p.id 
    WHERE q.pasien_id = ? 
    AND q.tanggal = CURDATE() 
    AND q.status IN ("menunggu", "dipanggil")
    LIMIT 1
');
$stmt->execute([$patient['id']]);
$active_queue = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pasien - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Dashboard Pasien</h1>
                
                <div class="card">
                    <h3>Data Pribadi</h3>
                    <p><strong>Nama:</strong> <?php echo htmlspecialchars($patient['nama']); ?></p>
                    <p><strong>NIK:</strong> <?php echo htmlspecialchars($patient['nik']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($patient['email']); ?></p>
                </div>
                
                <?php if ($active_queue): ?>
                    <div class="card card-info">
                        <h3>Antrean Anda Hari Ini</h3>
                        <p><strong>Poli:</strong> <?php echo htmlspecialchars($active_queue['nama_poli']); ?></p>
                        <p><strong>Nomor Antrean:</strong> <span class="badge"><?php echo htmlspecialchars($active_queue['nomor_antrian']); ?></span></p>
                        <p><strong>Status:</strong> <span class="badge badge-<?php echo $active_queue['status'] === 'dipanggil' ? 'warning' : 'info'; ?>"><?php echo ucfirst($active_queue['status']); ?></span></p>
                        <p><strong>Estimasi Waktu:</strong> <?php echo $active_queue['estimasi_waktu'] ?? '-'; ?></p>
                        <a href="ambil_antrian.php" class="btn btn-secondary">Batal Antrean</a>
                    </div>
                <?php else: ?>
                    <div class="card">
                        <h3>Anda Belum Memiliki Antrean Hari Ini</h3>
                        <a href="ambil_antrian.php" class="btn btn-primary">Ambil Antrean</a>
                    </div>
                <?php endif; ?>
                
                <div class="card">
                    <h3>Menu</h3>
                    <ul>
                        <li><a href="ambil_antrian.php">Ambil Antrean</a></li>
                        <li><a href="riwayat.php">Riwayat Kunjungan</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
