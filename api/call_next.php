<?php
/**
 * API: Panggil Pasien Berikutnya
 * Mengubah status antrean berikutnya menjadi 'dipanggil'
 * 
 * @package Smart Queue System
 * @subpackage API
 */

header('Content-Type: application/json');

require_once '../config/database.php';
require_once '../includes/auth_middleware.php';

// Cek session dan role (hanya petugas/dokter/admin)
check_auth(['petugas', 'dokter', 'admin']);

try {
    $poli_id = $_POST['poli_id'] ?? null;
    
    if (!$poli_id) {
        throw new Exception('Poli ID tidak diberikan');
    }
    
    // Query: Ambil antrean dengan prioritas tertinggi status menunggu
    $stmt = $pdo->prepare("
        SELECT id, nomor_antrian 
        FROM queues 
        WHERE poli_id = ? 
        AND tanggal = CURDATE() 
        AND status = 'menunggu' 
        ORDER BY 
            CASE kategori_prioritas
                WHEN 'darurat' THEN 1
                WHEN 'lansia' THEN 2
                WHEN 'hamil' THEN 2
                WHEN 'disabilitas' THEN 2
                WHEN 'normal' THEN 3
            END,
            jam_daftar ASC 
        LIMIT 1
    ");
    $stmt->execute([$poli_id]);
    $queue = $stmt->fetch();
    
    if (!$queue) {
        throw new Exception('Tidak ada antrean yang menunggu');
    }
    
    // Update status antrean
    $stmt = $pdo->prepare("UPDATE queues SET status = 'dipanggil' WHERE id = ?");
    $stmt->execute([$queue['id']]);
    
    echo json_encode([
        'success' => true,
        'queue_number' => $queue['nomor_antrian'],
        'message' => 'Pasien berhasil dipanggil'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>
