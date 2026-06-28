<?php
require_once '../config/database.php';
require_once '../includes/api_helpers.php';

api_require_method('POST');
api_require_auth(['petugas', 'admin']);
api_require_csrf();

$poliId = positive_int($_POST['poli_id'] ?? null);
if (!$poliId) {
    api_response(false, ['message' => 'Poli ID tidak valid'], 422);
}

try {
    $pdo->beginTransaction();
    $poli = $pdo->prepare('SELECT id FROM poli WHERE id=? AND is_active=1 FOR UPDATE');
    $poli->execute([$poliId]);
    if (!$poli->fetch()) {
        throw new DomainException('Poli tidak ditemukan atau tidak aktif');
    }

    $activeStmt = $pdo->prepare("SELECT id, status FROM queues WHERE poli_id=? AND tanggal=CURDATE()
        AND status IN ('dipanggil','dalam_pemeriksaan') FOR UPDATE");
    $activeStmt->execute([$poliId]);
    $activeQueues = $activeStmt->fetchAll();

    $stmt = $pdo->prepare("SELECT id, nomor_antrian FROM queues WHERE poli_id=? AND tanggal=CURDATE()
        AND status='menunggu' ORDER BY CASE kategori_prioritas WHEN 'darurat' THEN 1
        WHEN 'lansia' THEN 2 WHEN 'hamil' THEN 2 WHEN 'disabilitas' THEN 2 ELSE 3 END,
        jam_daftar, id LIMIT 1 FOR UPDATE");
    $stmt->execute([$poliId]);
    $queue = $stmt->fetch();
    if (!$queue) {
        throw new DomainException('Tidak ada antrean yang menunggu');
    }

    // Jika ada antrean aktif sebelumnya, ubah statusnya secara otomatis agar pemanggilan berikutnya tidak terhambat:
    // - Jika statusnya 'dipanggil' (dipanggil tapi belum masuk periksa), otomatis diubah menjadi 'tidak_hadir'
    // - Jika statusnya 'dalam_pemeriksaan' (sedang diperiksa), otomatis diubah menjadi 'selesai'
    foreach ($activeQueues as $act) {
        $nextStatus = ($act['status'] === 'dipanggil') ? 'tidak_hadir' : 'selesai';
        $updateActive = $pdo->prepare("UPDATE queues SET status=? WHERE id=?");
        $updateActive->execute([$nextStatus, $act['id']]);
    }

    $update = $pdo->prepare("UPDATE queues SET status='dipanggil' WHERE id=? AND status='menunggu'");
    $update->execute([$queue['id']]);
    $pdo->commit();

    api_response(true, [
        'queue_id' => (int) $queue['id'],
        'queue_number' => $queue['nomor_antrian'],
        'message' => 'Pasien berhasil dipanggil',
    ]);
} catch (DomainException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    api_response(false, ['message' => $e->getMessage()], 409);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    api_response(false, ['message' => 'Gagal memanggil antrean berikutnya'], 500);
}
