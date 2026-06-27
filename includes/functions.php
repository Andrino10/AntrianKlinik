<?php
/**
 * Fungsi Helper untuk Smart Queue System
 * Berisi fungsi-fungsi reusable untuk generate nomor antrean, estimasi waktu, dll.
 * 
 * @package Smart Queue System
 * @subpackage Includes
 */

/**
 * Generate nomor antrean otomatis
 * Format: KODE_POLI + NOMOR (contoh: A001, G002, K003)
 */
function generate_queue_number($pdo, $poli_id) {
    try {
        // Ambil informasi poli
        $stmt = $pdo->prepare('SELECT kode_poli FROM poli WHERE id = ?');
        $stmt->execute([$poli_id]);
        $poli = $stmt->fetch();
        
        if (!$poli) {
            throw new Exception('Poli tidak ditemukan');
        }
        
        $kode_poli = $poli['kode_poli'];
        
        // Ambil nomor terakhir untuk poli dan tanggal hari ini
        $stmt = $pdo->prepare('
            SELECT MAX(CAST(SUBSTRING(nomor_antrian, LENGTH(?) + 1) AS UNSIGNED)) as last_number 
            FROM queues 
            WHERE poli_id = ? AND tanggal = CURDATE()
        ');
        $stmt->execute([$kode_poli, $poli_id]);
        $result = $stmt->fetch();
        
        $last_number = $result['last_number'] ?? 0;
        $new_number = $last_number + 1;
        
        // Format dengan leading zero (contoh: A001)
        $queue_number = $kode_poli . str_pad($new_number, 3, '0', STR_PAD_LEFT);
        
        return $queue_number;
        
    } catch (Exception $e) {
        throw new Exception('Error generate nomor antrean: ' . $e->getMessage());
    }
}

/**
 * Hitung estimasi waktu pelayanan
 * Formula: Jumlah antrean di depan × Rata-rata waktu per pasien
 */
function calculate_estimated_time($pdo, $poli_id) {
    try {
        // Ambil rata-rata waktu layanan poli
        $stmt = $pdo->prepare('SELECT avg_service_time FROM poli WHERE id = ?');
        $stmt->execute([$poli_id]);
        $poli = $stmt->fetch();
        $avg_time = $poli['avg_service_time'] ?? 10; // default 10 menit
        
        // Hitung jumlah antrean aktif yang di depan
        $stmt = $pdo->prepare('
            SELECT COUNT(*) as total 
            FROM queues 
            WHERE poli_id = ? 
            AND tanggal = CURDATE() 
            AND status IN ("menunggu", "dipanggil")
        ');
        $stmt->execute([$poli_id]);
        $queue_count = $stmt->fetch()['total'];
        
        // Estimasi waktu (dalam menit)
        $estimated_minutes = $queue_count * $avg_time;
        
        // Hitung jam estimasi
        $now = new DateTime();
        $estimated_time = $now->add(new DateInterval('PT' . $estimated_minutes . 'M'));
        
        return $estimated_time->format('Y-m-d H:i:s');
        
    } catch (Exception $e) {
        return null;
    }
}

/**
 * Validasi email
 */
function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Validasi NIK (16 digit)
 */
function validate_nik($nik) {
    return strlen($nik) === 16 && is_numeric($nik);
}

/**
 * Hitung umur dari tanggal lahir
 */
function calculate_age($birth_date) {
    $birth = new DateTime($birth_date);
    $today = new DateTime();
    $age = $today->diff($birth)->y;
    return $age;
}

/**
 * Cek apakah pasien termasuk lansia (≥60 tahun)
 */
function is_elderly($birth_date) {
    return calculate_age($birth_date) >= 60;
}

/**
 * Format tanggal Indonesia
 */
function format_date_id($date) {
    $months = [
        'January' => 'Januari', 'February' => 'Februari', 'March' => 'Maret',
        'April' => 'April', 'May' => 'Mei', 'June' => 'Juni',
        'July' => 'Juli', 'August' => 'Agustus', 'September' => 'September',
        'October' => 'Oktober', 'November' => 'November', 'December' => 'Desember'
    ];
    
    $date_obj = new DateTime($date);
    $formatted = $date_obj->format('d') . ' ' . $months[$date_obj->format('F')] . ' ' . $date_obj->format('Y');
    
    return $formatted;
}

/**
 * Log aktivitas sistem
 */
function log_activity($pdo, $user_id, $action, $description) {
    try {
        // Jika ada tabel activity_logs, bisa di-log di sini
        // Untuk sekarang, hanya placeholder
        return true;
    } catch (Exception $e) {
        return false;
    }
}

?>
