/**
 * Realtime Monitoring Script
 * Script untuk AJAX polling monitoring antrean
 * 
 * @package Smart Queue System
 * @subpackage Assets/JS
 */

let pollingInterval = null;
const POLLING_INTERVAL = 5000; // 5 detik

document.addEventListener('DOMContentLoaded', function() {
    // Mulai polling saat halaman dimuat
    startPolling();
});

/**
 * Start polling untuk update realtime
 */
function startPolling() {
    // Polling setiap 5 detik
    pollingInterval = setInterval(updateQueueStatus, POLLING_INTERVAL);
    
    // Update langsung saat dimuat
    updateQueueStatus();
}

/**
 * Stop polling
 */
function stopPolling() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
        pollingInterval = null;
    }
}

/**
 * Update status antrean dari API
 */
function updateQueueStatus() {
    fetch('../api/queue_status.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update current number
                const currentElement = document.getElementById('current-queue-number');
                if (currentElement) {
                    currentElement.textContent = data.current_number || '-';
                }
                
                // Update remaining queue
                const remainingElement = document.getElementById('remaining-queue');
                if (remainingElement) {
                    remainingElement.textContent = data.remaining_queue || 0;
                }
                
                // Update total today
                const totalElement = document.getElementById('total-queue-today');
                if (totalElement) {
                    totalElement.textContent = data.total_today || 0;
                }
                
                // Update timestamp
                const timestampElement = document.getElementById('update-timestamp');
                if (timestampElement) {
                    timestampElement.textContent = new Date(data.timestamp).toLocaleTimeString('id-ID');
                }
            }
        })
        .catch(error => {
            console.error('Error updating queue status:', error);
        });
}

/**
 * Panggil pasien berikutnya untuk poli tertentu
 * @param {number} poliId 
 */
function callNextPatient(poliId) {
    if (!confirmAction('Panggil pasien berikutnya?')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('poli_id', poliId);
    
    fetch('../api/call_next.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Nomor antrean: ' + data.queue_number, 'success');
            // Update tampilan antrean
            updateQueueList(poliId);
        } else {
            showAlert(data.message || 'Gagal memanggil pasien', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'danger');
    });
}

/**
 * Update daftar antrean untuk poli tertentu
 * @param {number} poliId 
 */
function updateQueueList(poliId) {
    const queueList = document.getElementById(`queue-${poliId}`);
    if (!queueList) return;
    
    // Simulasi update - dalam implementasi nyata, fetch dari API
    updateQueueStatus();
}

/**
 * Handle perubahan status antrean
 * @param {number} queueId 
 * @param {string} newStatus 
 */
function updateQueueStatus(queueId, newStatus) {
    const formData = new FormData();
    formData.append('queue_id', queueId);
    formData.append('status', newStatus);
    
    fetch('../api/update_status.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            // Refresh data
            updateQueueStatus();
        } else {
            showAlert(data.message || 'Gagal update status', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showAlert('Terjadi kesalahan', 'danger');
    });
}

/**
 * Tandai pasien tidak hadir
 * @param {number} queueId 
 */
function markNoShow(queueId) {
    if (!confirmAction('Tandai pasien ini tidak hadir?')) {
        return;
    }
    
    updateQueueStatus(queueId, 'tidak_hadir');
}

/**
 * Batalkan antrean
 * @param {number} queueId 
 */
function cancelQueue(queueId) {
    if (!confirmAction('Batalkan antrean ini?')) {
        return;
    }
    
    updateQueueStatus(queueId, 'dibatalkan');
}

/**
 * Tandai selesai pemeriksaan
 * @param {number} queueId 
 */
function finishService(queueId) {
    if (!confirmAction('Tandai pemeriksaan selesai?')) {
        return;
    }
    
    updateQueueStatus(queueId, 'selesai');
}

