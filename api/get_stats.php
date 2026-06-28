<?php
require_once '../config/database.php';
require_once '../includes/api_helpers.php';

api_require_method('GET');
api_require_auth(['petugas', 'dokter', 'admin']);

try {
    $summary = $pdo->query("SELECT COUNT(*) total_today,
        SUM(status = 'menunggu') waiting_today,
        SUM(status = 'dipanggil') called_today,
        SUM(status = 'dalam_pemeriksaan') in_service_today,
        SUM(status = 'selesai') completed_today,
        SUM(status = 'dibatalkan') cancelled_today,
        SUM(status = 'tidak_hadir') no_show_today
        FROM queues WHERE tanggal = CURDATE()" )->fetch();
    $totalPatients = (int) $pdo->query('SELECT COUNT(*) FROM patients')->fetchColumn();
    $perPoli = $pdo->query("SELECT p.nama_poli, COUNT(q.id) total
        FROM poli p LEFT JOIN queues q ON q.poli_id = p.id AND q.tanggal = CURDATE()
        WHERE p.is_active = 1 GROUP BY p.id, p.nama_poli ORDER BY p.nama_poli")->fetchAll();
    $wait = $pdo->query("SELECT COALESCE(ROUND(AVG(p.avg_service_time * x.ahead)), 0)
        FROM (SELECT q.id, q.poli_id, (SELECT COUNT(*) FROM queues q2 WHERE q2.poli_id=q.poli_id
        AND q2.tanggal=CURDATE() AND q2.status IN ('dipanggil','dalam_pemeriksaan','menunggu')
        AND q2.jam_daftar < q.jam_daftar) ahead FROM queues q
        WHERE q.tanggal=CURDATE() AND q.status='menunggu') x JOIN poli p ON p.id=x.poli_id")->fetchColumn();

    api_response(true, [
        'total_patients' => $totalPatients,
        'total_today' => (int) ($summary['total_today'] ?? 0),
        'waiting_today' => (int) ($summary['waiting_today'] ?? 0),
        'called_today' => (int) ($summary['called_today'] ?? 0),
        'in_service_today' => (int) ($summary['in_service_today'] ?? 0),
        'completed_today' => (int) ($summary['completed_today'] ?? 0),
        'cancelled_today' => (int) ($summary['cancelled_today'] ?? 0),
        'no_show_today' => (int) ($summary['no_show_today'] ?? 0),
        'estimated_wait_minutes' => (int) $wait,
        'queue_per_poli' => $perPoli,
    ]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    api_response(false, ['message' => 'Gagal mengambil statistik'], 500);
}
