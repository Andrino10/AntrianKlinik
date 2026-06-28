/** Realtime queue management. All mutations originate from explicit clicks. */
const QUEUE_POLL_INTERVAL = 5000;
let queuePollTimer = null;
let queueRequestController = null;

document.addEventListener('DOMContentLoaded', () => {
    if (!document.querySelector('.poli-panel')) return;
    document.addEventListener('click', handleQueueClick);
    refreshQueueList();
    queuePollTimer = window.setInterval(refreshQueueList, QUEUE_POLL_INTERVAL);
    document.addEventListener('visibilitychange', handleVisibilityChange);
});

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.content || '';
}

async function fetchJson(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: { Accept: 'application/json', ...(options.headers || {}) }
    });
    const data = await response.json().catch(() => ({ success: false, message: 'Response server tidak valid' }));
    if (!response.ok || !data.success) throw new Error(data.message || 'Request gagal');
    return data;
}

function handleVisibilityChange() {
    if (document.hidden) {
        window.clearInterval(queuePollTimer);
        queuePollTimer = null;
        queueRequestController?.abort();
    } else if (!queuePollTimer) {
        refreshQueueList();
        queuePollTimer = window.setInterval(refreshQueueList, QUEUE_POLL_INTERVAL);
    }
}

async function refreshQueueList() {
    queueRequestController?.abort();
    queueRequestController = new AbortController();
    try {
        const data = await fetchJson('../api/get_queue_list.php', { signal: queueRequestController.signal });
        renderQueues(data.queues || [], data.summary || {});
        const timestamp = document.getElementById('update-timestamp');
        if (timestamp) timestamp.textContent = new Date(data.timestamp).toLocaleTimeString('id-ID');
    } catch (error) {
        if (error.name !== 'AbortError') showAlert(error.message, 'danger');
    }
}

function renderQueues(queues, summary) {
    const byPoli = new Map();
    document.querySelectorAll('.poli-panel').forEach(panel => byPoli.set(Number(panel.dataset.poliId), []));
    queues.forEach(queue => byPoli.get(Number(queue.poli_id))?.push(queue));
    byPoli.forEach((items, poliId) => {
        const container = document.getElementById(`queue-${poliId}`);
        if (container) container.innerHTML = items.length ? queueTable(items) : '<p class="queue-empty">Tidak ada antrean aktif.</p>';
    });
    ['total', 'menunggu', 'dipanggil', 'dalam_pemeriksaan'].forEach(status => setStat(status, summary[status] || 0));
}

function queueTable(items) {
    return `<div class="table-responsive"><table class="table queue-table"><thead><tr>
        <th>No.</th><th>Pasien</th><th>Kontak</th><th>Prioritas</th><th>Waktu</th><th>Status</th><th>Aksi</th>
        </tr></thead><tbody>${items.map(queueRow).join('')}</tbody></table></div>`;
}

function queueRow(queue) {
    const estimated = queue.estimasi_waktu ? formatDateTime(queue.estimasi_waktu) : '-';
    return `<tr>
        <td><strong>${escapeHtml(queue.nomor_antrian)}</strong></td>
        <td>${escapeHtml(queue.nama)}<br><small>NIK: ${escapeHtml(queue.nik)}</small></td>
        <td>${escapeHtml(queue.no_hp)}</td>
        <td>${escapeHtml(labelStatus(queue.kategori_prioritas))}</td>
        <td>${formatDateTime(queue.jam_daftar)}<br><small>Estimasi: ${estimated}</small></td>
        <td><span class="badge badge-${escapeHtml(queue.status)}">${escapeHtml(labelStatus(queue.status))}</span></td>
        <td class="queue-actions">${actionButtons(queue)}</td>
    </tr>`;
}

function actionButtons(queue) {
    const id = Number(queue.id);
    if (queue.status === 'menunggu') return actionButton(id, 'dibatalkan', 'Batalkan', 'secondary');
    if (queue.status === 'dipanggil') return [
        actionButton(id, 'dipanggil', 'Panggil Ulang', 'warning'),
        actionButton(id, 'tidak_hadir', 'Tidak Hadir', 'danger'),
        actionButton(id, 'dalam_pemeriksaan', 'Mulai Pemeriksaan', 'success'),
        actionButton(id, 'dibatalkan', 'Batalkan', 'secondary')
    ].join(' ');
    if (queue.status === 'dalam_pemeriksaan') return [
        actionButton(id, 'selesai', 'Selesai', 'success'),
        actionButton(id, 'dibatalkan', 'Batalkan', 'secondary')
    ].join(' ');
    return '';
}

function actionButton(id, status, label, style) {
    return `<button type="button" class="btn btn-sm btn-${style}" data-queue-action="${status}" data-queue-id="${id}">${label}</button>`;
}

async function handleQueueClick(event) {
    const nextButton = event.target.closest('.btn-call-next');
    if (nextButton) {
        const panel = nextButton.closest('.poli-panel');
        if (confirmAction('Panggil pasien berikutnya?')) await mutateQueue('../api/call_next.php', { poli_id: panel.dataset.poliId });
        return;
    }
    const button = event.target.closest('[data-queue-action]');
    if (!button) return;
    const labels = { dipanggil: 'Panggil ulang pasien?', tidak_hadir: 'Tandai pasien tidak hadir?', dalam_pemeriksaan: 'Mulai pemeriksaan?', selesai: 'Selesaikan pemeriksaan?', dibatalkan: 'Batalkan antrean?' };
    if (confirmAction(labels[button.dataset.queueAction] || 'Lanjutkan aksi?')) {
        await mutateQueue('../api/update_status.php', { queue_id: button.dataset.queueId, status: button.dataset.queueAction });
    }
}

async function mutateQueue(url, values) {
    const body = new FormData();
    Object.entries(values).forEach(([key, value]) => body.append(key, value));
    document.querySelectorAll('button[data-queue-action], .btn-call-next').forEach(button => button.disabled = true);
    try {
        const data = await fetchJson(url, { method: 'POST', body, headers: { 'X-CSRF-Token': csrfToken() } });
        showAlert(data.message, 'success');
        await refreshQueueList();
    } catch (error) {
        showAlert(error.message, 'danger');
    } finally {
        document.querySelectorAll('button[data-queue-action], .btn-call-next').forEach(button => button.disabled = false);
    }
}

function setStat(name, value) {
    const element = document.querySelector(`[data-stat="${name}"]`);
    if (element) element.textContent = value;
}

function labelStatus(value) {
    return String(value || '').replaceAll('_', ' ').replace(/\b\w/g, letter => letter.toUpperCase());
}

function formatDateTime(value) {
    const normalized = String(value).replace(' ', 'T');
    return new Date(normalized).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
}

function escapeHtml(value) {
    const node = document.createElement('div');
    node.textContent = String(value ?? '');
    return node.innerHTML;
}
