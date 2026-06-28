<?php
/**
 * Header Komponen
 * Template header yang ditampilkan di setiap halaman
 * 
 * @package Smart Queue System
 * @subpackage Includes
 */
?>
<?php
require_once __DIR__ . '/api_helpers.php';
?>
<meta name="csrf-token" content="<?php echo htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8'); ?>">
<div class="header">
    <div class="header-content">
        <div class="logo">
            <h2>Smart Queue System</h2>
        </div>
        
        <div class="user-menu">
            <span class="user-name"><?php echo htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></span>
            <span class="user-role">(<?php echo ucfirst($_SESSION['role'] ?? ''); ?>)</span>
            <a href="<?php echo get_base_url(); ?>/auth/logout.php" class="btn btn-sm btn-danger">Logout</a>
        </div>
    </div>
</div>
