<?php
/**
 * Sidebar Komponen
 * Menu navigasi sidebar yang ditampilkan di setiap halaman
 * 
 * @package Smart Queue System
 * @subpackage Includes
 */

$role = $_SESSION['role'] ?? '';
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <nav class="sidebar-menu">
        <?php if ($role === 'pasien'): ?>
            <h3>Menu Pasien</h3>
            <ul>
                <li><a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="ambil_antrian.php" class="<?php echo $current_page === 'ambil_antrian.php' ? 'active' : ''; ?>">Ambil Antrean</a></li>
                <li><a href="riwayat.php" class="<?php echo $current_page === 'riwayat.php' ? 'active' : ''; ?>">Riwayat Kunjungan</a></li>
            </ul>
        
        <?php elseif ($role === 'petugas'): ?>
            <h3>Menu Petugas</h3>
            <ul>
                <li><a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="antrian.php" class="<?php echo $current_page === 'antrian.php' ? 'active' : ''; ?>">Kelola Antrean</a></li>
                <li><a href="pasien.php" class="<?php echo $current_page === 'pasien.php' ? 'active' : ''; ?>">Data Pasien</a></li>
            </ul>
        
        <?php elseif ($role === 'dokter'): ?>
            <h3>Menu Dokter</h3>
            <ul>
                <li><a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="pelayanan.php" class="<?php echo $current_page === 'pelayanan.php' ? 'active' : ''; ?>">Pelayanan Pasien</a></li>
            </ul>
        
        <?php elseif ($role === 'admin'): ?>
            <h3>Menu Admin</h3>
            <ul>
                <li><a href="dashboard.php" class="<?php echo $current_page === 'dashboard.php' ? 'active' : ''; ?>">Dashboard</a></li>
                <li><a href="users.php" class="<?php echo $current_page === 'users.php' ? 'active' : ''; ?>">Kelola Pengguna</a></li>
                <li><a href="poli.php" class="<?php echo $current_page === 'poli.php' ? 'active' : ''; ?>">Kelola Poli</a></li>
            </ul>
        <?php endif; ?>
    </nav>
</div>
