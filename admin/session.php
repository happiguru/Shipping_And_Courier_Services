<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    session_set_cookie_params(['httponly'=>true,'secure'=>$isHttps,'samesite'=>'Lax','path'=>'/']);
    session_start();
}
require_once __DIR__ . '/../database/connection.php';
if (empty($_SESSION['userId']) || empty($_SESSION['username'])) {
    header('Location: index.php'); exit();
}
try {
    $stmt = app_db()->prepare('SELECT adminid, username FROM admin WHERE adminid = :id AND username = :username LIMIT 1');
    $stmt->execute([':id'=>(int)$_SESSION['userId'], ':username'=>(string)$_SESSION['username']]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    error_log('Admin session validation failed: '.$exception->getMessage());
    http_response_code(503); exit('Admin services are temporarily unavailable.');
}
if (!$admin) {
    $_SESSION = [];
    if (session_status() === PHP_SESSION_ACTIVE) session_destroy();
    header('Location: index.php'); exit();
}
$login_session = $admin['username'];
if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
