<?php
/**
 * API: Data Statistik Dashboard
 * Mengembalikan data statistik untuk dashboard admin/petugas
 * 
 * @package Smart Queue System
 * @subpackage API
 */

header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['petugas', 'dokter', 'admin']);

try {
    // Total pasien terdaftar
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM patients");
    $total_patients = $stmt->fetch()['total'];
    
    // Total antrean hari ini
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE()");
    $total_today = $stmt->fetch()['total'];
    
    // Pasien selesai hari ini
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE() AND status = 'selesai'");
    $completed_today = $stmt->fetch()['total'];
    
    // Pembatalan hari ini
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE() AND status = 'dibatalkan'");
    $cancelled_today = $stmt->fetch()['total'];
    
    // Tidak hadir hari ini
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM queues WHERE tanggal = CURDATE() AND status = 'tidak_hadir'");
    $no_show_today = $stmt->fetch()['total'];
    
    // Antrean per poli hari ini
    $stmt = $pdo->query("
        SELECT p.nama_poli, COUNT(q.id) as total 
        FROM queues q 
        JOIN poli p ON q.poli_id = p.id 
        WHERE q.tanggal = CURDATE() 
        GROUP BY q.poli_id, p.nama_poli
    ");
    $queue_per_poli = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'total_patients' => $total_patients,
        'total_today' => $total_today,
        'completed_today' => $completed_today,
        'cancelled_today' => $cancelled_today,
        'no_show_today' => $no_show_today,
        'queue_per_poli' => $queue_per_poli
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
