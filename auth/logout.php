<?php
/**
 * Halaman Logout
 * Menghapus session pengguna dan redirect ke halaman login
 * 
 * @package Smart Queue System
 * @subpackage Auth
 */

session_start();
session_destroy();
header('Location: login.php');
exit;
?>
