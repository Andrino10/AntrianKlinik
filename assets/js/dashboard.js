let dashboardPollTimer = null;
let dashboardController = null;

document.addEventListener('DOMContentLoaded', () => {
    if (!document.querySelector('[data-dashboard-stat]')) return;
    refreshDashboardStats();
    dashboardPollTimer = window.setInterval(refreshDashboardStats, 5000);
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            window.clearInterval(dashboardPollTimer);
            dashboardPollTimer = null;
            dashboardController?.abort();
        } else if (!dashboardPollTimer) {
            refreshDashboardStats();
            dashboardPollTimer = window.setInterval(refreshDashboardStats, 5000);
        }
    });
});

async function refreshDashboardStats() {
    dashboardController?.abort();
    dashboardController = new AbortController();
    try {
        const response = await fetch('../api/get_stats.php', { headers: { Accept: 'application/json' }, signal: dashboardController.signal });
        const data = await response.json();
        if (!response.ok || !data.success) throw new Error(data.message || 'Gagal mengambil statistik');
        document.querySelectorAll('[data-dashboard-stat]').forEach(element => {
            const key = element.dataset.dashboardStat;
            if (Object.prototype.hasOwnProperty.call(data, key)) element.textContent = data[key];
        });
    } catch (error) {
        if (error.name !== 'AbortError') console.error('Dashboard:', error);
    }
}
