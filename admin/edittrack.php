<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly'=>true,'secure'=>$isHttps,'samesite'=>'Lax','path'=>'/']);
    session_start();
}
if (empty($_SESSION['userId']) || empty($_SESSION['username'])) { header('Location: index.php'); exit(); }
$id=filter_var($_GET['id'] ?? null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
if (!$id) { header('Location: dashboard.php'); exit(); }
require_once __DIR__.'/../database/connection.php';
try {
    $stmt=app_db()->prepare('SELECT * FROM tracking WHERE id=:id LIMIT 1');
    $stmt->execute([':id'=>$id]); $row=$stmt->fetch(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    error_log('Edit shipment lookup failed: '.$exception->getMessage());
    http_response_code(503); exit('Shipment details are temporarily unavailable.');
}
if (!$row) { header('Location: dashboard.php'); exit(); }
$id=(int)$row['id']; $num=$row['tracking_number']; $shipdate=$row['shipment_date']; $estimate_date=$row['delivery_date'];
$ship_type=$row['shipment_type']; $content=$row['content']; $r_firstname=$row['first_name']; $r_lastname=$row['last_name'];
$source_city=$row['source_city']; $source_state=$row['source_state']; $source_country=$row['source_zip'];
$current_city=$row['current_city']; $current_state=$row['current_state']; $current_country=$row['current_zip'];
$destination_city=$row['destination_city']; $destination_state=$row['destination_state']; $destination_country=$row['destination_zip'];
$phone=$row['contact_number']; $status=$row['parcel_status'];
