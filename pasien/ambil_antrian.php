<?php
/**
 * Ambil Antrean
 * Halaman untuk pasien mengambil nomor antrean
 * 
 * @package Smart Queue System
 * @subpackage Pasien
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';
require_once '../includes/functions.php';

check_auth(['pasien']);

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Ambil data pasien
$stmt = $pdo->prepare('SELECT id, tanggal_lahir FROM patients WHERE user_id = ?');
$stmt->execute([$user_id]);
$patient = $stmt->fetch();

// Ambil daftar poli
$stmt = $pdo->query('SELECT * FROM poli WHERE is_active = 1');
$poli_list = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $poli_id = $_POST['poli_id'] ?? null;
    $priority = $_POST['priority'] ?? 'normal';
    
    if (!$poli_id) {
        $error = 'Pilih poli terlebih dahulu';
    } else {
        try {
            // Cek antrean aktif
            $stmt = $pdo->prepare('
                SELECT id FROM queues 
                WHERE pasien_id = ? 
                AND tanggal = CURDATE() 
                AND status IN ("menunggu", "dipanggil")
            ');
            $stmt->execute([$patient['id']]);
            if ($stmt->fetch()) {
                throw new Exception('Anda sudah memiliki antrean aktif hari ini');
            }
            
            // Generate nomor antrean
            $queue_number = generate_queue_number($pdo, $poli_id);
            $estimasi_waktu = calculate_estimated_time($pdo, $poli_id);
            
            // Insert ke database
            $stmt = $pdo->prepare('
                INSERT INTO queues (nomor_antrian, pasien_id, poli_id, tanggal, jam_daftar, status, kategori_prioritas, estimasi_waktu) 
                VALUES (?, ?, ?, CURDATE(), NOW(), "menunggu", ?, ?)
            ');
            $stmt->execute([$queue_number, $patient['id'], $poli_id, $priority, $estimasi_waktu]);
            
            $success = 'Antrean berhasil diambil! Nomor antrean Anda: ' . $queue_number;
            
        } catch (Exception $e) {
            $error = 'Error: ' . $e->getMessage();
        }
    }
}

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ambil Antrean - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Ambil Antrean</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                    <a href="dashboard.php" class="btn btn-primary">Kembali ke Dashboard</a>
                <?php else: ?>
                    <div class="card">
                        <form method="POST" action="">
                            <div class="form-group">
                                <label for="poli_id">Pilih Poli</label>
                                <select id="poli_id" name="poli_id" required class="form-control">
                                    <option value="">-- Pilih Poli --</option>
                                    <?php foreach ($poli_list as $p): ?>
                                        <option value="<?php echo $p['id']; ?>">
                                            <?php echo htmlspecialchars($p['nama_poli']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="form-group">
                                <label for="priority">Kategori Prioritas</label>
                                <select id="priority" name="priority" class="form-control">
                                    <option value="normal">Normal</option>
                                    <option value="lansia">Lansia (≥60 tahun)</option>
                                    <option value="hamil">Ibu Hamil</option>
                                    <option value="disabilitas">Penyandang Disabilitas</option>
                                    <option value="darurat">Pasien Darurat</option>
                                </select>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">Ambil Antrean</button>
                            <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                        </form>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
