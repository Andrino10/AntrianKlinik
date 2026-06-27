<?php
/**
 * Halaman Monitoring Publik / Landing Page
 * Halaman utama yang dapat diakses oleh siapa saja untuk melihat status antrean
 * 
 * @package Smart Queue System
 */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitoring Antrean - Smart Queue System</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/responsive.css">
    <style>
        .monitoring-container {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background: linear-gradient(135deg, #2C7BE5, #1f5ec4);
        }
        
        .monitoring-header {
            background: rgba(0, 0, 0, 0.2);
            color: white;
            text-align: center;
            padding: 30px;
        }
        
        .monitoring-header h1 {
            font-size: 36px;
            margin-bottom: 10px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }
        
        .monitoring-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .monitoring-content {
            flex: 1;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        .queue-display {
            background: white;
            border-radius: 12px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            padding: 40px;
            max-width: 600px;
            width: 100%;
        }
        
        .queue-card {
            text-align: center;
            margin-bottom: 30px;
            padding: 25px;
            background: #f9fbfd;
            border-radius: 10px;
            border-left: 4px solid #2C7BE5;
        }
        
        .queue-card h2 {
            color: #6B7280;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 10px;
        }
        
        .queue-card .value {
            font-size: 56px;
            font-weight: bold;
            color: #2C7BE5;
            font-family: 'Courier New', monospace;
        }
        
        .queue-info {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
        
        .queue-info p {
            margin: 10px 0;
            font-size: 16px;
            color: #12263F;
        }
        
        .queue-info strong {
            color: #2C7BE5;
        }
        
        .update-time {
            text-align: center;
            margin-top: 30px;
            font-size: 13px;
            color: #6B7280;
        }
        
        .nav-links {
            text-align: center;
            padding: 30px 20px;
            background: white;
            border-top: 1px solid #e0e3e8;
        }
        
        .nav-links a {
            display: inline-block;
            margin: 0 15px;
            padding: 12px 30px;
            background: #2C7BE5;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.3s;
            font-weight: 500;
        }
        
        .nav-links a:hover {
            background: #1f5ec4;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(44, 123, 229, 0.3);
        }
    </style>
</head>
<body class="monitoring-container">
    <div class="monitoring-header">
        <h1>📋 Sistem Monitoring Antrean</h1>
        <p>Smart Queue System - Klinik/Puskesmas Digital</p>
    </div>
    
    <div class="monitoring-content">
        <div class="queue-display">
            <div class="queue-card">
                <h2>Nomor Sedang Dipanggil</h2>
                <div class="value" id="current-queue-number">-</div>
            </div>
            
            <div class="queue-card">
                <h2>Nomor Berikutnya</h2>
                <div class="value" style="font-size: 36px; color: #F6C343;" id="next-queue-number">-</div>
            </div>
            
            <div class="queue-info">
                <p><strong>Antrean Tersisa:</strong> <span id="remaining-queue">0</span> orang</p>
                <p><strong>Total Antrean Hari Ini:</strong> <span id="total-queue-today">0</span> orang</p>
                <p class="update-time">Data terakhir diperbarui: <span id="update-timestamp">--:--:--</span></p>
            </div>
            
            <div style="text-align: center; color: #6B7280; font-size: 13px; margin-top: 20px;">
                ⚡ Data diperbarui otomatis setiap 5 detik
            </div>
        </div>
    </div>
    
    <div class="nav-links">
        <a href="auth/register.php">📝 Daftar Akun</a>
        <a href="auth/login.php">🔐 Login</a>
    </div>
    
    <script src="assets/js/main.js"></script>
    <script>
        // Update monitoring setiap 5 detik
        let pollingInterval = null;
        
        function updateMonitoring() {
            fetch('api/queue_status.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('current-queue-number').textContent = data.current_number || '-';
                        document.getElementById('remaining-queue').textContent = data.remaining_queue || 0;
                        document.getElementById('total-queue-today').textContent = data.total_today || 0;
                        document.getElementById('update-timestamp').textContent = 
                            new Date(data.timestamp).toLocaleTimeString('id-ID');
                    }
                })
                .catch(error => console.error('Error:', error));
        }
        
        // Update langsung saat halaman dimuat
        updateMonitoring();
        
        // Polling setiap 5 detik
        pollingInterval = setInterval(updateMonitoring, 5000);
    </script>
</body>
</html>
