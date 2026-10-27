<?php
declare(strict_types=1);

require_once __DIR__ . '/../database/connection.php';

function smoke_assert(bool $condition, string $message): void
{
    if (!$condition) {
        throw new RuntimeException('SQLite smoke test failed: ' . $message);
    }
}

$db = app_db();
smoke_assert((int)$db->query('PRAGMA foreign_keys')->fetchColumn() === 1, 'foreign keys should be enabled');

$tables = ['admin', 'users', 'tracking', 'tracking_events', 'contact_messages', 'quote_requests', 'admin_login_attempts'];
foreach ($tables as $table) {
    $stmt = $db->prepare("SELECT 1 FROM sqlite_master WHERE type = 'table' AND name = :name");
    $stmt->execute([':name' => $table]);
    smoke_assert((bool)$stmt->fetchColumn(), 'missing table ' . $table);
}

$db->beginTransaction();
try {
    $admin = $db->prepare('INSERT INTO admin (username, passw) VALUES (:username, :passw)');
    $admin->execute([':username' => 'smoke-admin', ':passw' => password_hash('not-a-production-password', PASSWORD_DEFAULT)]);
    $adminId = (int)$db->lastInsertId();

    $shipment = $db->prepare("INSERT INTO tracking
        (first_name,last_name,tracking_number,shipment_type,content,shipment_date,delivery_date,
         source_city,source_state,source_zip,current_city,current_state,current_zip,
         destination_city,destination_state,destination_zip,contact_number,parcel_status)
        VALUES (:first_name,:last_name,:tracking_number,:shipment_type,:content,:shipment_date,:delivery_date,
         :source_city,:source_state,:source_zip,:current_city,:current_state,:current_zip,
         :destination_city,:destination_state,:destination_zip,:contact_number,:parcel_status)");
    $shipment->execute([
        ':first_name' => 'Test', ':last_name' => 'Recipient', ':tracking_number' => 'NGSMOKETEST001',
        ':shipment_type' => 'Land Freight', ':content' => 'Test parcel', ':shipment_date' => '2026-10-27',
        ':delivery_date' => '2026-10-29', ':source_city' => 'Origin', ':source_state' => 'State',
        ':source_zip' => '', ':current_city' => 'Hub', ':current_state' => 'State', ':current_zip' => '',
        ':destination_city' => 'Destination', ':destination_state' => 'State', ':destination_zip' => '',
        ':contact_number' => '0000000000', ':parcel_status' => 'On Hold',
    ]);
    $shipmentId = (int)$db->lastInsertId();

    $event = $db->prepare('INSERT INTO tracking_events (shipment_id,tracking_number,status,location,description,created_by) VALUES (?,?,?,?,?,?)');
    $event->execute([$shipmentId, 'NGSMOKETEST001', 'On Hold', 'Hub, State', 'Smoke test', $adminId]);

    $contact = $db->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (?,?,?,?)');
    $contact->execute(['Test User', 'test@example.invalid', 'Smoke test', 'Test contact message']);

    $quote = $db->prepare('INSERT INTO quote_requests (first_name,last_name,email,shipment_type) VALUES (?,?,?,?)');
    $quote->execute(['Test', 'User', 'test@example.invalid', 'Land Freight']);

    $db->commit();
} catch (Throwable $exception) {
    if ($db->inTransaction()) $db->rollBack();
    throw $exception;
}

smoke_assert((int)$db->query("SELECT COUNT(*) FROM tracking_events WHERE tracking_number = 'NGSMOKETEST001'")->fetchColumn() === 1, 'tracking event should be persisted');
smoke_assert((int)$db->query("SELECT COUNT(*) FROM contact_messages WHERE email = 'test@example.invalid'")->fetchColumn() === 1, 'contact message should be persisted');
smoke_assert((int)$db->query("SELECT COUNT(*) FROM quote_requests WHERE email = 'test@example.invalid'")->fetchColumn() === 1, 'quote request should be persisted');

$duplicateRejected = false;
try {
    $db->exec("INSERT INTO tracking (first_name,last_name,tracking_number,shipment_type,shipment_date,delivery_date,source_city,source_state,current_city,current_state,destination_city,destination_state,contact_number,parcel_status) VALUES ('A','B','NGSMOKETEST001','Land Freight','2026-10-27','2026-10-29','A','B','A','B','C','D','0','On Hold')");
} catch (PDOException $exception) {
    $duplicateRejected = true;
}
smoke_assert($duplicateRejected, 'duplicate tracking numbers should be rejected');

echo "SQLite smoke test passed.\n";
