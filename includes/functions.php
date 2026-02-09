<?php
require_once __DIR__ . '/../config/settings.php';

function start_secure_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        ini_set('session.cookie_httponly', '1');
        ini_set('session.cookie_secure', '0');
        ini_set('session.cookie_samesite', 'Strict');
        session_start();
    }
}

function ensure_csrf_token(): string
{
    start_secure_session();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validate_csrf_token(?string $token): void
{
    start_secure_session();
    if (!$token || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        die('CSRF token validation failed');
    }
}

function sanitize_output(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function enforce_session_timeout(): void
{
    start_secure_session();
    $timeoutSeconds = SESSION_TIMEOUT_MINUTES * 60;
    $lastActivity = $_SESSION['last_activity'] ?? time();

    if ((time() - $lastActivity) > $timeoutSeconds) {
        session_unset();
        session_destroy();
        header('Location: /auth/login.php?timeout=1');
        exit;
    }

    $_SESSION['last_activity'] = time();
}
?>
