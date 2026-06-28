<?php
require_once '../config/database.php';
require_once '../includes/api_helpers.php';

api_require_method('GET');
api_require_auth(['petugas', 'admin']);

$poliId = isset($_GET['poli_id']) ? positive_int($_GET['poli_id']) : null;
if (isset($_GET['poli_id']) && !$poliId) {
    api_response(false, ['message' => 'Poli ID tidak valid'], 422);
}

try {
    $sql = "SELECT q.id, q.nomor_antrian, q.poli_id, q.jam_daftar, q.estimasi_waktu,
        q.kategori_prioritas, q.status, p.nama, p.nik, p.no_hp, pol.nama_poli
        FROM queues q JOIN patients p ON p.id=q.pasien_id JOIN poli pol ON pol.id=q.poli_id
        WHERE q.tanggal=CURDATE() AND q.status IN ('menunggu','dipanggil','dalam_pemeriksaan')";
    $params = [];
    if ($poliId) { $sql .= ' AND q.poli_id=?'; $params[] = $poliId; }
    $sql .= " ORDER BY q.poli_id, FIELD(q.status,'dalam_pemeriksaan','dipanggil','menunggu'),
        CASE q.kategori_prioritas WHEN 'darurat' THEN 1 WHEN 'lansia' THEN 2
        WHEN 'hamil' THEN 2 WHEN 'disabilitas' THEN 2 ELSE 3 END, q.jam_daftar, q.id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $summary = $pdo->query("SELECT COUNT(*) total, SUM(status='menunggu') menunggu,
        SUM(status='dipanggil') dipanggil, SUM(status='dalam_pemeriksaan') dalam_pemeriksaan
        FROM queues WHERE tanggal=CURDATE()")->fetch();
    api_response(true, [
        'queues' => $stmt->fetchAll(),
        'summary' => array_map('intval', $summary),
        'timestamp' => date(DATE_ATOM),
    ]);
} catch (Throwable $e) {
    error_log($e->getMessage());
    api_response(false, ['message' => 'Gagal mengambil daftar antrean'], 500);
}
