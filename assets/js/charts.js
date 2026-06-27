/**
 * Chart.js Initialization Script
 * Script untuk inisialisasi dan render Chart.js
 * 
 * @package Smart Queue System
 * @subpackage Assets/JS
 */

let charts = {};

/**
 * Initialize charts di dashboard admin
 * @param {array} poliLabels 
 * @param {array} poliData 
 * @param {array} trendLabels 
 * @param {array} trendData 
 * @param {array} statusLabels 
 * @param {array} statusData 
 */
function initializeCharts(poliLabels, poliData, trendLabels, trendData, statusLabels, statusData) {
    // Bar Chart - Antrean per Poli
    if (document.getElementById('poliChart')) {
        charts.poliChart = new Chart(
            document.getElementById('poliChart').getContext('2d'),
            {
                type: 'bar',
                data: {
                    labels: poliLabels,
                    datasets: [{
                        label: 'Jumlah Antrean',
                        data: poliData,
                        backgroundColor: '#2C7BE5',
                        borderColor: '#1f5ec4',
                        borderWidth: 1,
                        borderRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            }
        );
    }
    
    // Line Chart - Tren 7 hari
    if (document.getElementById('trendChart')) {
        charts.trendChart = new Chart(
            document.getElementById('trendChart').getContext('2d'),
            {
                type: 'line',
                data: {
                    labels: trendLabels,
                    datasets: [{
                        label: 'Total Antrean',
                        data: trendData,
                        borderColor: '#2C7BE5',
                        backgroundColor: 'rgba(44, 123, 229, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointBackgroundColor: '#2C7BE5',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            }
        );
    }
    
    // Doughnut Chart - Distribusi Status
    if (document.getElementById('statusChart')) {
        const colors = {
            'menunggu': '#2C7BE5',
            'dipanggil': '#F6C343',
            'dalam_pemeriksaan': '#00D97E',
            'selesai': '#00B366',
            'tidak_hadir': '#E63757',
            'dibatalkan': '#6B7280'
        };
        
        const backgroundColors = statusLabels.map(label => colors[label] || '#2C7BE5');
        
        charts.statusChart = new Chart(
            document.getElementById('statusChart').getContext('2d'),
            {
                type: 'doughnut',
                data: {
                    labels: statusLabels.map(label => capitalizeStatus(label)),
                    datasets: [{
                        data: statusData,
                        backgroundColor: backgroundColors,
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 15,
                                usePointStyle: true
                            }
                        }
                    }
                }
            }
        );
    }
}

/**
 * Capitalize status name untuk display
 * @param {string} status 
 */
function capitalizeStatus(status) {
    const statusMap = {
        'menunggu': 'Menunggu',
        'dipanggil': 'Dipanggil',
        'dalam_pemeriksaan': 'Dalam Pemeriksaan',
        'selesai': 'Selesai',
        'tidak_hadir': 'Tidak Hadir',
        'dibatalkan': 'Dibatalkan'
    };
    
    return statusMap[status] || status;
}

/**
 * Update chart dengan data baru
 * @param {string} chartName 
 * @param {array} newLabels 
 * @param {array} newData 
 */
function updateChart(chartName, newLabels, newData) {
    if (charts[chartName]) {
        charts[chartName].data.labels = newLabels;
        charts[chartName].data.datasets[0].data = newData;
        charts[chartName].update();
    }
}

/**
 * Destroy all charts
 */
function destroyAllCharts() {
    Object.keys(charts).forEach(key => {
        if (charts[key]) {
            charts[key].destroy();
        }
    });
    charts = {};
}

