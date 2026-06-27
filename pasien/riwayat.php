<?php
/**
 * Riwayat Kunjungan
 * Halaman untuk pasien melihat riwayat kunjungan mereka
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
$stmt = $pdo->prepare('SELECT id FROM patients WHERE user_id = ?');
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

// Ambil riwayat kunjungan
$stmt = $pdo->prepare('
    SELECT q.*, p.nama_poli, sr.waktu_mulai, sr.waktu_selesai, sr.catatan 
    FROM queues q 
    LEFT JOIN poli p ON q.poli_id = p.id 
    LEFT JOIN service_records sr ON q.id = sr.queue_id 
    WHERE q.pasien_id = ? 
    ORDER BY q.tanggal DESC, q.jam_daftar DESC
');
$stmt->execute([$patient['id']]);
$history = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kunjungan - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Riwayat Kunjungan</h1>
                
                <?php if (empty($history)): ?>
                    <div class="card">
                        <p>Anda belum memiliki riwayat kunjungan.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Poli</th>
                                    <th>Nomor Antrean</th>
                                    <th>Status</th>
                                    <th>Estimasi Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($history as $h): ?>
                                    <tr>
                                        <td><?php echo date('d/m/Y', strtotime($h['tanggal'])); ?></td>
                                        <td><?php echo htmlspecialchars($h['nama_poli'] ?? '-'); ?></td>
                                        <td><?php echo htmlspecialchars($h['nomor_antrian']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $h['status']; ?>">
                                                <?php echo ucfirst($h['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo $h['estimasi_waktu'] ?? '-'; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>
