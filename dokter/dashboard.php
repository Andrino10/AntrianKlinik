<?php
/**
 * Dashboard Dokter
 * Halaman utama dokter untuk melihat daftar pasien di polinya
 * 
 * @package Smart Queue System
 * @subpackage Dokter
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['dokter', 'admin']);

$user_id = $_SESSION['user_id'];

// Ambil daftar pasien untuk dokter (dari service_records)
$stmt = $pdo->prepare('
    SELECT DISTINCT q.*, p.nama, pol.nama_poli 
    FROM queues q 
    JOIN patients p ON q.pasien_id = p.id 
    JOIN poli pol ON q.poli_id = pol.id 
    JOIN service_records sr ON q.id = sr.queue_id 
    WHERE sr.dokter_id = ? 
    AND q.tanggal = CURDATE() 
    ORDER BY q.status DESC, q.jam_daftar ASC
');
$stmt->execute([$user_id]);
$patients = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Dashboard Dokter</h1>
                
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nomor Antrean</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($patients as $p): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($p['nomor_antrian']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nama']); ?></td>
                                    <td><?php echo htmlspecialchars($p['nama_poli']); ?></td>
                                    <td>
                                        <span class="badge badge-<?php echo $p['status']; ?>">
                                            <?php echo ucfirst($p['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($p['status'] === 'dalam_pemeriksaan'): ?>
                                            <button class="btn btn-sm btn-success btn-finish-service" data-queue-id="<?php echo $p['id']; ?>">
                                                Selesai
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>
