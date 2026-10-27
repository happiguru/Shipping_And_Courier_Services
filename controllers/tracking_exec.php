<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}
require_once __DIR__ . '/../database/connection.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Allow: POST'); http_response_code(405); exit('Method not allowed'); }
$trackingNumber = trim((string)($_POST['trackingnumber'] ?? ''));
if ($trackingNumber === '' || strlen($trackingNumber) > 100 || !preg_match('/^[A-Za-z0-9_-]+$/', $trackingNumber)) {
    $_SESSION['ERRMSG_ARR'] = ['Enter a valid tracking number.']; header('Location: ../views/tracking.php'); exit();
}
if (!$db_login instanceof PDO) {
    $_SESSION['ERRMSG_ARR'] = ['Tracking is temporarily unavailable. Please try again shortly.']; header('Location: ../views/tracking.php'); exit();
}
try {
    $stmt = $db_login->prepare('SELECT tracking_number FROM tracking WHERE tracking_number = :tracking_number LIMIT 1');
    $stmt->execute([':tracking_number' => $trackingNumber]);
    $shipment = $stmt->fetch();
} catch (Throwable $exception) {
    error_log('Tracking lookup failed: ' . $exception->getMessage());
    $_SESSION['ERRMSG_ARR'] = ['Tracking is temporarily unavailable. Please try again shortly.']; header('Location: ../views/tracking.php'); exit();
}
if ($shipment) {
    session_regenerate_id(true); $_SESSION['USER_NUM'] = $shipment['tracking_number']; header('Location: ../views/tracking_details.php'); exit();
}
$_SESSION['ERRMSG_ARR'] = ['We could not find that tracking number. Please check it and try again.'];
header('Location: ../views/tracking.php'); exit();
