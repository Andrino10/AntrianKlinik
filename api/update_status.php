<?php
/**
 * API: Update Status Antrean
 * Mengubah status antrean sesuai permintaan
 * 
 * @package Smart Queue System
 * @subpackage API
 */

header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

check_auth(['petugas', 'dokter', 'admin']);

try {
    $queue_id = $_POST['queue_id'] ?? null;
    $new_status = $_POST['status'] ?? null;
    
    if (!$queue_id || !$new_status) {
        throw new Exception('Data tidak lengkap');
    }
    
    // Validasi status yang diizinkan
    $valid_statuses = ['menunggu', 'dipanggil', 'dalam_pemeriksaan', 'selesai', 'tidak_hadir', 'dibatalkan'];
    if (!in_array($new_status, $valid_statuses)) {
        throw new Exception('Status tidak valid');
    }
    
    // Update status
    $stmt = $pdo->prepare("UPDATE queues SET status = ? WHERE id = ?");
    $stmt->execute([$new_status, $queue_id]);
    
    echo json_encode([
        'success' => true,
        'message' => 'Status antrean berhasil diperbarui'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
