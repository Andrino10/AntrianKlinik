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
$error = '';

// Handler untuk memulai pemeriksaan pasien
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'start_examination') {
    $queue_id = $_POST['queue_id'] ?? null;
    if ($queue_id) {
        try {
            $stmt = $pdo->prepare("UPDATE queues SET status = 'dalam_pemeriksaan' WHERE id = ? AND status = 'dipanggil'");
            $stmt->execute([$queue_id]);
            header("Location: pelayanan.php?queue_id=" . $queue_id);
            exit;
        } catch (Exception $e) {
            $error = 'Gagal memulai pemeriksaan: ' . $e->getMessage();
        }
    }
}

// Ambil daftar antrean yang sedang aktif hari ini (dipanggil atau dalam_pemeriksaan) di semua poli
$active_stmt = $pdo->query("
    SELECT q.*, p.nama AS nama_pasien, pol.nama_poli 
    FROM queues q 
    JOIN patients p ON q.pasien_id = p.id 
    JOIN poli pol ON q.poli_id = pol.id 
    WHERE q.tanggal = CURDATE() 
    AND q.status IN ('dipanggil', 'dalam_pemeriksaan')
    ORDER BY FIELD(q.status, 'dalam_pemeriksaan', 'dipanggil'), q.jam_daftar ASC
");
$active_patients = $active_stmt->fetchAll();

// Ambil log/riwayat lengkap pelayanan pasien (semua poli, semua waktu)
$log_stmt = $pdo->query("
    SELECT q.nomor_antrian, q.tanggal, q.jam_daftar, q.status, 
           p.nama AS nama_pasien, pol.nama_poli, 
           sr.waktu_mulai, sr.waktu_selesai, sr.catatan,
           u.nama AS nama_dokter
    FROM queues q
    JOIN patients p ON q.pasien_id = p.id
    JOIN poli pol ON q.poli_id = pol.id
    LEFT JOIN service_records sr ON q.id = sr.queue_id
    LEFT JOIN users u ON sr.dokter_id = u.id
    WHERE q.status IN ('selesai', 'tidak_hadir', 'dibatalkan', 'dalam_pemeriksaan')
    ORDER BY q.tanggal DESC, q.jam_daftar DESC
");
$all_logs = $log_stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Dokter - Smart Queue System</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/css/responsive.css">
    <style>
        .badge-menunggu { background: #eff6ff; color: #0c2e4e; }
        .badge-dipanggil { background: #fffbeb; color: #92400e; }
        .badge-dalam_pemeriksaan { background: #fdf2f8; color: #9d174d; }
        .badge-selesai { background: #ecfdf5; color: #065f46; }
        .badge-tidak_hadir { background: #fef2f2; color: #7f1d1d; }
        .badge-dibatalkan { background: #f3f4f6; color: #374151; }
        
        .section-title {
            margin-top: 30px;
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: 600;
            color: var(--dark);
            border-bottom: 2px solid var(--border);
            padding-bottom: 8px;
        }
        
        .table-responsive {
            margin-bottom: 40px;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <?php include '../includes/sidebar.php'; ?>
        
        <div class="main-content">
            <?php include '../includes/header.php'; ?>
            
            <div class="content-area">
                <h1>Dashboard Dokter</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- BAGIAN 1: ANTRENAN AKTIF -->
                <div class="section-title">🩺 Antrean Aktif Saat Ini (Semua Poli)</div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Antrean</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($active_patients)): ?>
                                <tr>
                                    <td colspan="5" style="text-align: center; color: var(--text-light);">Tidak ada antrean aktif saat ini.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($active_patients as $p): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($p['nomor_antrian']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($p['nama_pasien']); ?></td>
                                        <td><?php echo htmlspecialchars($p['nama_poli']); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $p['status']; ?>">
                                                <?php 
                                                    $lbl = $p['status'] === 'dalam_pemeriksaan' ? 'Dalam Pemeriksaan' : 'Dipanggil';
                                                    echo htmlspecialchars($lbl); 
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($p['status'] === 'dalam_pemeriksaan'): ?>
                                                <a href="pelayanan.php?queue_id=<?php echo $p['id']; ?>" class="btn btn-sm btn-success">
                                                    Catat Pemeriksaan / Selesai
                                                </a>
                                            <?php elseif ($p['status'] === 'dipanggil'): ?>
                                                <form method="POST" action="" style="display:inline;">
                                                    <input type="hidden" name="action" value="start_examination">
                                                    <input type="hidden" name="queue_id" value="<?php echo $p['id']; ?>">
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        Mulai Pemeriksaan
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- BAGIAN 2: LOG PELAYANAN PASIEN -->
                <div class="section-title">📋 Riwayat Pelayanan Pasien (Semua Poli)</div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Tanggal / Waktu Daftar</th>
                                <th>No. Antrean</th>
                                <th>Nama Pasien</th>
                                <th>Poli</th>
                                <th>Durasi / Waktu Layanan</th>
                                <th>Dokter</th>
                                <th>Status</th>
                                <th>Catatan Medis</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all_logs)): ?>
                                <tr>
                                    <td colspan="8" style="text-align: center; color: var(--text-light);">Belum ada riwayat pelayanan.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($all_logs as $log): ?>
                                    <tr>
                                        <td>
                                            <?php 
                                                $tgl = date('d-m-Y', strtotime($log['tanggal']));
                                                $jam = date('H:i', strtotime($log['jam_daftar']));
                                                echo htmlspecialchars("$tgl / $jam"); 
                                            ?>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($log['nomor_antrian']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($log['nama_pasien']); ?></td>
                                        <td><?php echo htmlspecialchars($log['nama_poli']); ?></td>
                                        <td>
                                            <?php 
                                                if ($log['status'] === 'selesai' && $log['waktu_mulai'] && $log['waktu_selesai']) {
                                                    $mulai = date('H:i', strtotime($log['waktu_mulai']));
                                                    $selesai = date('H:i', strtotime($log['waktu_selesai']));
                                                    echo htmlspecialchars("$mulai s.d $selesai");
                                                } elseif ($log['status'] === 'dalam_pemeriksaan' && $log['waktu_mulai']) {
                                                    $mulai = date('H:i', strtotime($log['waktu_mulai']));
                                                    echo htmlspecialchars("Mulai: $mulai (Aktif)");
                                                } else {
                                                    echo '-';
                                                }
                                            ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($log['nama_dokter'] ?? '-'); ?></td>
                                        <td>
                                            <span class="badge badge-<?php echo $log['status']; ?>">
                                                <?php 
                                                    $status_labels = [
                                                        'selesai' => 'Selesai',
                                                        'tidak_hadir' => 'Tidak Hadir',
                                                        'dibatalkan' => 'Batal',
                                                        'dalam_pemeriksaan' => 'Dalam Pemeriksaan'
                                                    ];
                                                    echo htmlspecialchars($status_labels[$log['status']] ?? $log['status']); 
                                                ?>
                                            </span>
                                        </td>
                                        <td>
                                            <small><?php echo htmlspecialchars($log['catatan'] ?? '-'); ?></small>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <script src="../assets/js/main.js"></script>
</body>
</html>
