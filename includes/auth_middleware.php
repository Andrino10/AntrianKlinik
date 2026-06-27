<?php
/**
 * Authentication Middleware
 * Fungsi untuk validasi session dan role pengguna
 * 
 * @package Smart Queue System
 * @subpackage Includes
 */

/**
 * Cek apakah pengguna sudah login
 * Jika tidak, redirect ke halaman login
 */
function check_auth($allowed_roles = []) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // Cek session
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . get_base_url() . '/auth/login.php');
        exit;
    }
    
    // Cek role jika ada pembatasan
    if (!empty($allowed_roles)) {
        if (!in_array($_SESSION['role'], $allowed_roles)) {
            header('HTTP/1.0 403 Forbidden');
            die('Anda tidak memiliki akses ke halaman ini');
        }
    }
}

/**
 * Dapatkan base URL aplikasi
 */
function get_base_url() {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $project_folder = basename(dirname(__DIR__));
    
    return $protocol . '://' . $host . '/' . $project_folder;
}

/**
 * Redirect ke URL tertentu
 */
function redirect($url) {
    header('Location: ' . $url);
    exit;
}

/**
 * Sanitasi input string
 */
function sanitize_input($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Cek apakah request adalah POST
 */
function is_post_request() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Cek apakah request adalah AJAX
 */
function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

?>
