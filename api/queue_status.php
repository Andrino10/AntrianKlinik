<?php
require_once '../config/database.php';
require_once '../includes/api_helpers.php';

api_require_method('GET');

try {
    $currentQueues = $pdo->query("SELECT q.nomor_antrian, q.poli_id, p.nama_poli
        FROM queues q JOIN poli p ON p.id=q.poli_id
        WHERE q.tanggal=CURDATE() AND q.status IN ('dipanggil','dalam_pemeriksaan')
        ORDER BY q.poli_id, FIELD(q.status,'dipanggil','dalam_pemeriksaan'), q.jam_daftar")->fetchAll();
    $next = $pdo->query("SELECT nomor_antrian FROM queues WHERE tanggal=CURDATE() AND status='menunggu'
        ORDER BY CASE kategori_prioritas WHEN 'darurat' THEN 1 WHEN 'lansia' THEN 2
        WHEN 'hamil' THEN 2 WHEN 'disabilitas' THEN 2 ELSE 3 END, jam_daftar LIMIT 1")->fetchColumn();
    $counts = $pdo->query("SELECT COUNT(*) total_today,
        SUM(status IN ('menunggu','dipanggil','dalam_pemeriksaan')) remaining_queue
        FROM queues WHERE tanggal=CURDATE()")->fetch();

    api_response(true, [
        'current_number' => $currentQueues[0]['nomor_antrian'] ?? null,
        'current_queues' => $currentQueues,
        'next_number' => $next ?: null,
        'remaining_queue' => (int) ($counts['remaining_queue'] ?? 0),
        'total_today' => (int) ($counts['total_today'] ?? 0),
        'timestamp' => date(DATE_ATOM),
    ]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    api_response(false, ['message' => 'Gagal mengambil status antrean'], 500);
}
