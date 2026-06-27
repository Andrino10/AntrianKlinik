<?php
/**
 * Pelayanan Pasien (Dokter)
 * Halaman untuk dokter melayani pasien
 * 
 * @package Smart Queue System
 * @subpackage Dokter
 */

session_start();
require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['dokter', 'admin']);

$user_id = $_SESSION['user_id'];
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $queue_id = $_POST['queue_id'] ?? null;
    $catatan = htmlspecialchars($_POST['catatan'] ?? '');
    
    if (!$queue_id) {
        $error = 'Queue ID tidak diberikan';
    } else {
        try {
            // Update status antrean
            $stmt = $pdo->prepare('UPDATE queues SET status = "selesai" WHERE id = ?');
            $stmt->execute([$queue_id]);
            
            // Insert ke service_records
            $stmt = $pdo->prepare('
                INSERT INTO service_records (queue_id, dokter_id, waktu_mulai, waktu_selesai, catatan) 
                VALUES (?, ?, NOW(), NOW(), ?)
            ');
            $stmt->execute([$queue_id, $user_id, $catatan]);
            
            $success = 'Pemeriksaan pasien berhasil dicatat';
            
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
    <title>Pelayanan Pasien - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Pelayanan Pasien</h1>
                
                <?php if ($success): ?>
                    <div class="alert alert-success"><?php echo $success; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div class="card">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="queue_id">Nomor Antrian</label>
                            <input type="text" id="queue_id" name="queue_id" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="catatan">Catatan Pemeriksaan</label>
                            <textarea id="catatan" name="catatan" class="form-control"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Selesaikan Pemeriksaan</button>
                        <a href="dashboard.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
