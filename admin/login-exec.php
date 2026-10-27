<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}
require_once __DIR__ . '/../database/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Allow: POST'); http_response_code(405); exit('Method not allowed');
}
if (empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
    $_SESSION['ERRMSG_ARR'] = ['Your login session expired. Please try again.']; header('Location: index.php'); exit();
}
$username = trim((string)($_POST['username'] ?? ''));
$password = (string)($_POST['password'] ?? '');
if ($username === '' || $password === '' || strlen($username) > 190 || strlen($password) > 4096) {
    $_SESSION['ERRMSG_ARR'] = ['Enter your username and password.']; header('Location: index.php'); exit();
}
if (!$db_login instanceof PDO) {
    $_SESSION['ERRMSG_ARR'] = ['Login is temporarily unavailable. Please try again later.']; header('Location: index.php'); exit();
}
// Persistent per-IP throttling makes repeated password guessing slower across sessions.
$remoteAddress = (string)($_SERVER['REMOTE_ADDR'] ?? 'unknown');
$ipHash = hash('sha256', $remoteAddress);
$now = time();
try {
    $rate = $db_login->prepare('SELECT attempts, window_started_at, locked_until FROM admin_login_attempts WHERE ip_hash = :ip_hash LIMIT 1');
    $rate->execute([':ip_hash' => $ipHash]);
    $rateRow = $rate->fetch();
    if ($rateRow && (int)$rateRow['locked_until'] > $now) {
        $waitMinutes = max(1, (int)ceil(((int)$rateRow['locked_until'] - $now) / 60));
        $_SESSION['ERRMSG_ARR'] = ["Too many sign-in attempts. Try again in {$waitMinutes} minute(s)."];
        header('Location: index.php'); exit();
    }
} catch (Throwable $exception) {
    error_log('Admin login throttle lookup failed: ' . $exception->getMessage());
    $_SESSION['ERRMSG_ARR'] = ['Login is temporarily unavailable. Please try again later.']; header('Location: index.php'); exit();
}
try {
    $stmt = $db_login->prepare('SELECT adminid, username, passw FROM admin WHERE username = :username LIMIT 1');
    $stmt->execute([':username' => $username]);
    $row = $stmt->fetch();
} catch (Throwable $exception) {
    error_log('Admin login query failed: ' . $exception->getMessage());
    $_SESSION['ERRMSG_ARR'] = ['Login is temporarily unavailable. Please try again later.']; header('Location: index.php'); exit();
}
$authenticated = false;
if ($row && isset($row['passw'])) {
    $storedPassword = (string)$row['passw'];
    if (password_verify($password, $storedPassword)) {
        $authenticated = true;
    } elseif (hash_equals($storedPassword, $password)) {
        $authenticated = true;
        try {
            $upgrade = $db_login->prepare('UPDATE admin SET passw = :passw WHERE adminid = :adminid');
            $upgrade->execute([':passw' => password_hash($password, PASSWORD_DEFAULT), ':adminid' => (int)$row['adminid']]);
        } catch (Throwable $exception) {
            error_log('Legacy admin password upgrade failed: ' . $exception->getMessage());
        }
    }
}
if (!$authenticated) {
    try {
        $db_login->beginTransaction();
        $check = $db_login->prepare('SELECT attempts, window_started_at FROM admin_login_attempts WHERE ip_hash = :ip_hash LIMIT 1');
        $check->execute([':ip_hash' => $ipHash]);
        $attemptRow = $check->fetch();
        if (!$attemptRow || ($now - (int)$attemptRow['window_started_at']) >= 900) {
            $attempts = 1;
            $windowStarted = $now;
        } else {
            $attempts = (int)$attemptRow['attempts'] + 1;
            $windowStarted = (int)$attemptRow['window_started_at'];
        }
        $lockedUntil = $attempts >= 8 ? $now + 900 : 0;
        $save = $db_login->prepare('INSERT INTO admin_login_attempts (ip_hash, attempts, window_started_at, locked_until) VALUES (:ip_hash, :attempts, :window_started_at, :locked_until) ON CONFLICT(ip_hash) DO UPDATE SET attempts = excluded.attempts, window_started_at = excluded.window_started_at, locked_until = excluded.locked_until');
        $save->execute([':ip_hash' => $ipHash, ':attempts' => $attempts, ':window_started_at' => $windowStarted, ':locked_until' => $lockedUntil]);
        $db_login->commit();
    } catch (Throwable $exception) {
        if ($db_login->inTransaction()) $db_login->rollBack();
        error_log('Admin login throttle update failed: ' . $exception->getMessage());
    }
    $_SESSION['ERRMSG_ARR'] = ['Invalid username or password.']; header('Location: index.php'); exit();
}
try {
    $clearRate = $db_login->prepare('DELETE FROM admin_login_attempts WHERE ip_hash = :ip_hash');
    $clearRate->execute([':ip_hash' => $ipHash]);
} catch (Throwable $exception) {
    error_log('Admin login throttle reset failed: ' . $exception->getMessage());
}
session_regenerate_id(true);
$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
$_SESSION['userId'] = (int)$row['adminid'];
$_SESSION['username'] = $row['username'];
unset($_SESSION['ERRMSG_ARR']);
header('Location: dashboard.php');
exit();
