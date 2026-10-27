<?php
require_once __DIR__ . '/session.php';
require_once __DIR__ . '/../database/connection.php';

function csv_safe_cell($value): string {
    $value = (string)$value;
    // Prevent spreadsheet formula execution when an exported cell starts with a formula marker.
    if ($value !== '' && preg_match('/^[\x00-\x20]*[=+@\-]/', $value)) {
        $value = "'" . $value;
    }
    return $value;
}

try {
    $rows = app_db()->query(
        'SELECT tracking_number, first_name, last_name, shipment_type, content, shipment_date, delivery_date,
                source_city, source_state, source_zip, current_city, current_state, current_zip,
                destination_city, destination_state, destination_zip, contact_number, parcel_status, created_at, updated_at
         FROM tracking ORDER BY id DESC'
    )->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $exception) {
    error_log('Shipment CSV export failed: ' . $exception->getMessage());
    http_response_code(503);
    exit('Shipment export is temporarily unavailable. Please try again.');
}

$filename = 'nextgen-shipments-' . date('Y-m-d') . '.csv';
header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');
$out = fopen('php://output', 'w');
if ($out === false) {
    http_response_code(500);
    exit('Unable to create the export.');
}
fwrite($out, "\xEF\xBB\xBF");
$headers = [
    'Tracking number', 'First name', 'Last name', 'Shipment type', 'Contents',
    'Shipment date', 'Delivery date', 'Origin city', 'Origin state', 'Origin ZIP',
    'Current city', 'Current state', 'Current ZIP', 'Destination city', 'Destination state',
    'Destination ZIP', 'Contact number', 'Status', 'Created at', 'Updated at'
];
fputcsv($out, $headers);
foreach ($rows as $row) {
    fputcsv($out, array_map('csv_safe_cell', array_values($row)));
}
fclose($out);
exit;
