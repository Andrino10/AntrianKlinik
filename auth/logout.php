<?php
/**
 * Halaman Logout
 * Menghapus seluruh session pengguna dan mengarahkan kembali ke halaman login
 *
 * @package Smart Queue System
 * @subpackage Auth
 */

session_start();

// Hapus seluruh data session
$_SESSION = [];

// Hapus variabel session
session_unset();

// Hancurkan session
session_destroy();

// Redirect ke halaman login
header("Location: login.php");
exit;
?>