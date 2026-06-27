<?php
/**
 * API: Status Antrean Realtime
 * Mengembalikan status antrean terkini dalam format JSON
 * Digunakan untuk AJAX polling di halaman monitoring publik
 * 
 * @package Smart Queue System
 * @subpackage API
 */

header('Content-Type: application/json');

require_once '../config/database.php';

try {
    // Query: Nomor sedang dipanggil
    $stmt = $pdo->query("
        SELECT nomor_antrian, poli_id 
        FROM queues 
        WHERE tanggal = CURDATE() 
        AND status = 'dipanggil' 
        LIMIT 1
    ");
    $current = $stmt->fetch();
    
    // Query: Total antrean tersisa
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM queues 
        WHERE tanggal = CURDATE() 
        AND status IN ('menunggu', 'dipanggil')
    ");
    $remaining = $stmt->fetch()['total'];
    
    // Query: Total antrean hari ini
    $stmt = $pdo->query("
        SELECT COUNT(*) as total 
        FROM queues 
        WHERE tanggal = CURDATE()
    ");
    $total_today = $stmt->fetch()['total'];
    
    echo json_encode([
        'success' => true,
        'current_number' => $current['nomor_antrian'] ?? null,
        'remaining_queue' => $remaining,
        'total_today' => $total_today,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
