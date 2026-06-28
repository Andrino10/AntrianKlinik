<?php
require_once '../config/database.php';
require_once '../includes/api_helpers.php';

api_require_method('POST');
api_require_auth(['petugas', 'dokter', 'admin']);
api_require_csrf();

$queueId = positive_int($_POST['queue_id'] ?? null);
$newStatus = $_POST['status'] ?? '';
$validStatuses = ['dipanggil', 'dalam_pemeriksaan', 'selesai', 'tidak_hadir', 'dibatalkan'];
if (!$queueId || !in_array($newStatus, $validStatuses, true)) {
    api_response(false, ['message' => 'Queue ID atau status tidak valid'], 422);
}

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare('SELECT id, status FROM queues WHERE id=? AND tanggal=CURDATE() FOR UPDATE');
    $stmt->execute([$queueId]);
    $queue = $stmt->fetch();
    if (!$queue) throw new DomainException('Antrean hari ini tidak ditemukan');

    $transitions = [
        'menunggu' => ['dibatalkan'],
        'dipanggil' => ['dipanggil', 'dalam_pemeriksaan', 'tidak_hadir', 'dibatalkan'],
        'dalam_pemeriksaan' => ['selesai', 'dibatalkan'],
        'selesai' => [], 'tidak_hadir' => [], 'dibatalkan' => [],
    ];
    if (!in_array($newStatus, $transitions[$queue['status']] ?? [], true)) {
        throw new DomainException('Perubahan status dari ' . $queue['status'] . ' ke ' . $newStatus . ' tidak diizinkan');
    }

    $update = $pdo->prepare('UPDATE queues SET status=? WHERE id=?');
    $update->execute([$newStatus, $queueId]);
    $pdo->commit();
    api_response(true, ['message' => 'Status antrean berhasil diperbarui']);
} catch (DomainException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    api_response(false, ['message' => $e->getMessage()], 409);
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    error_log($e->getMessage());
    api_response(false, ['message' => 'Gagal memperbarui status antrean'], 500);
}
