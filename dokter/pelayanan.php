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
        $error = 'Pasien belum dipilih atau ID Antrean tidak valid';
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

// Deteksi prefill dari parameter GET
$prefilled_id = $_GET['queue_id'] ?? '';
$selected_patient_info = null;

if (!empty($prefilled_id)) {
    $stmt = $pdo->prepare('
        SELECT q.nomor_antrian, p.nama AS nama_pasien, pol.nama_poli 
        FROM queues q 
        JOIN patients p ON q.pasien_id = p.id 
        JOIN poli pol ON q.poli_id = pol.id 
        WHERE q.id = ?
    ');
    $stmt->execute([$prefilled_id]);
    $selected_patient_info = $stmt->fetch();
}

// Ambil daftar pasien dengan status 'dalam_pemeriksaan' untuk dropdown jika tidak di-prefill
$active_stmt = $pdo->query("
    SELECT q.id, q.nomor_antrian, p.nama AS nama_pasien, pol.nama_poli
    FROM queues q
    JOIN patients p ON q.pasien_id = p.id
    JOIN poli pol ON q.poli_id = pol.id
    WHERE q.tanggal = CURDATE() AND q.status = 'dalam_pemeriksaan'
    ORDER BY q.jam_daftar ASC
");
$active_list = $active_stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelayanan Pasien - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <style>
        .patient-summary {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }
    </style>
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
                        
                        <?php if ($selected_patient_info): ?>
                            <!-- Pasien sudah di-prefill dari dashboard -->
                            <div class="patient-summary">
                                <strong>Pasien Sedang Dilayani:</strong><br>
                                Nama: <?php echo htmlspecialchars($selected_patient_info['nama_pasien']); ?><br>
                                Poli: <?php echo htmlspecialchars($selected_patient_info['nama_poli']); ?><br>
                                No. Antrean: <?php echo htmlspecialchars($selected_patient_info['nomor_antrian']); ?>
                            </div>
                            <input type="hidden" name="queue_id" value="<?php echo htmlspecialchars($prefilled_id); ?>">
                        <?php else: ?>
                            <!-- Dropdown pilihan pasien aktif -->
                            <div class="form-group">
                                <label for="queue_id">Pilih Pasien Aktif</label>
                                <select id="queue_id" name="queue_id" required class="form-control">
                                    <option value="">-- Pilih Pasien --</option>
                                    <?php foreach ($active_list as $act): ?>
                                        <option value="<?php echo $act['id']; ?>">
                                            <?php echo htmlspecialchars($act['nomor_antrian'] . ' - ' . $act['nama_pasien'] . ' (' . $act['nama_poli'] . ')'); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endif; ?>
                        
                        <div class="form-group">
                            <label for="catatan">Catatan Pemeriksaan</label>
                            <textarea id="catatan" name="catatan" class="form-control" rows="5" placeholder="Masukkan diagnosis, resep, atau catatan pelayanan..."></textarea>
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
