<?php
require_once __DIR__ . '/session.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], (string)($_POST['csrf_token'] ?? ''))) {
    header('Allow: POST'); http_response_code(405); exit('Method not allowed');
}
$id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
if (!$id) { header('Location: dashboard.php'); exit(); }
require_once __DIR__ . '/../database/connection.php';
if (!$db_login instanceof PDO) {
    $_SESSION['ERRMSG_ARR'] = ['Unable to delete shipment right now. Please try again.']; header('Location: dashboard.php'); exit();
}
try {
    $stmt = $db_login->prepare('DELETE FROM tracking WHERE id = :id');
    $stmt->execute([':id' => $id]);
} catch (Throwable $exception) {
    error_log('Delete shipment failed: ' . $exception->getMessage());
    $_SESSION['ERRMSG_ARR'] = ['Unable to delete shipment right now. Please try again.'];
}
header('Location: dashboard.php'); exit();
