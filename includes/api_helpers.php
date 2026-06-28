<?php

function api_response(bool $success, array $data = [], int $status = 200): void
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-store');
    echo json_encode(array_merge(['success' => $success], $data), JSON_UNESCAPED_UNICODE);
    exit;
}

function api_require_method(string $method): void
{
    if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== $method) {
        header('Allow: ' . $method);
        api_response(false, ['message' => 'Metode request tidak diizinkan'], 405);
    }
}

function api_require_auth(array $roles): void
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }

    if (empty($_SESSION['user_id'])) {
        api_response(false, ['message' => 'Sesi login telah berakhir'], 401);
    }

    if (!in_array($_SESSION['role'] ?? '', $roles, true)) {
        api_response(false, ['message' => 'Anda tidak memiliki akses'], 403);
    }
}

function csrf_token(): string
{
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function api_require_csrf(): void
{
    $provided = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($_POST['csrf_token'] ?? '');
    if (!is_string($provided) || !hash_equals(csrf_token(), $provided)) {
        api_response(false, ['message' => 'Token keamanan tidak valid'], 419);
    }
}

function positive_int($value): ?int
{
    $filtered = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
    return $filtered === false ? null : (int) $filtered;
}
