<?php
require_once __DIR__.'/session.php';
if ($_SERVER['REQUEST_METHOD']!=='POST' || !isset($_POST['submit']) || empty($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'],(string)($_POST['csrf_token'] ?? ''))) {
    http_response_code(403); exit('Invalid request. Please return to the shipment form and try again.');
}
$id=filter_var($_POST['id'] ?? null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
if (!$id) { header('Location: dashboard.php'); exit(); }
require_once __DIR__.'/../database/connection.php';
$redirect='edit.php?id='.$id;
$fields=['firstname'=>190,'lastname'=>190,'tracknum'=>100,'shipmentType'=>100,'content'=>2000,'sdate'=>30,'ddate'=>30,'scity'=>190,'sstate'=>190,'szip'=>40,'ccity'=>190,'cstate'=>190,'czip'=>40,'dcity'=>190,'dstate'=>190,'dzip'=>40,'contactnumber'=>60,'status'=>100];
$data=[];
foreach ($fields as $key=>$max) {
    $value=trim((string)($_POST[$key] ?? '')); $optional=in_array($key,['content','szip','czip','dzip'],true);
    if ((!$optional && $value==='') || strlen($value)>$max) { $_SESSION['ERRMSG_ARR']=['Please complete all shipment fields using valid values.']; header('Location: '.$redirect); exit(); }
    $data[]=$value;
}
$dateValid=static fn(string $date): bool => preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/',$date)===1 && checkdate((int)substr($date,5,2),(int)substr($date,8,2),(int)substr($date,0,4));
if (!in_array($data[3],['Air Freight','Marine Freight','Land Freight'],true) || !in_array($data[17],['Shipped','Delivered','Transit','Packaging','Missing Parcel','On Hold'],true)) { $_SESSION['ERRMSG_ARR']=['Choose a valid shipment type and parcel status.']; header('Location: '.$redirect); exit(); }
if (!$dateValid($data[5]) || !$dateValid($data[6]) || strcmp($data[6],$data[5])<0) { $_SESSION['ERRMSG_ARR']=['Enter valid dates. Delivery date cannot be earlier than shipment date.']; header('Location: '.$redirect); exit(); }
try {
    $db=app_db();
    $existing=$db->prepare('SELECT tracking_number, parcel_status, current_city, current_state FROM tracking WHERE id=? LIMIT 1');
    $existing->execute([(int)$id]);
    $existingRow=$existing->fetch(PDO::FETCH_ASSOC);
    $currentTrackingNumber=$existingRow['tracking_number'] ?? null;
    if (!is_string($currentTrackingNumber) || $currentTrackingNumber==='') {
        $_SESSION['ERRMSG_ARR']=['This shipment no longer exists. Refresh the dashboard and try again.'];
        header('Location: dashboard.php'); exit();
    }
    // Tracking IDs are generated at creation and cannot be changed by editing or forged POST data.
    $data[2]=$currentTrackingNumber;
    $sql='UPDATE tracking SET first_name=?,last_name=?,tracking_number=?,shipment_type=?,content=?,shipment_date=?,delivery_date=?,source_city=?,source_state=?,source_zip=?,current_city=?,current_state=?,current_zip=?,destination_city=?,destination_state=?,destination_zip=?,contact_number=?,parcel_status=?,updated_at=CURRENT_TIMESTAMP WHERE id=?';
    $data[]=(int)$id;
    $db->beginTransaction();
    $stmt=$db->prepare($sql);
    $stmt->execute($data);
    if ((string)($existingRow['parcel_status'] ?? '') !== $data[17]) {
        $event=$db->prepare('INSERT INTO tracking_events (shipment_id,tracking_number,status,location,description,event_time,created_by) VALUES (:shipment_id,:tracking_number,:status,:location,:description,CURRENT_TIMESTAMP,:created_by)');
        $event->execute([
            ':shipment_id'=>(int)$id, ':tracking_number'=>$currentTrackingNumber, ':status'=>$data[17],
            ':location'=>trim((string)($existingRow['current_city'] ?? '') . ', ' . (string)($existingRow['current_state'] ?? ''), ', '),
            ':description'=>'Shipment status updated by administrator', ':created_by'=>(int)($_SESSION['userId'] ?? 0) ?: null,
        ]);
    }
    $db->commit();
    if ($stmt->rowCount()===0) {
        $check=$db->prepare('SELECT 1 FROM tracking WHERE id=? LIMIT 1');
        $check->execute([(int)$id]);
        if (!$check->fetchColumn()) {
            $_SESSION['ERRMSG_ARR']=['This shipment no longer exists. Refresh the dashboard and try again.'];
            header('Location: dashboard.php'); exit();
        }
    }
} catch (PDOException $exception) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) $db->rollBack();
    error_log('Update shipment failed: '.$exception->getMessage());
    $_SESSION['ERRMSG_ARR']=['Unable to update shipment right now. Please try again.'];
    header('Location: '.$redirect); exit();
} catch (Throwable $exception) {
    if (isset($db) && $db instanceof PDO && $db->inTransaction()) $db->rollBack();
    error_log('Update shipment failed: '.$exception->getMessage());
    $_SESSION['ERRMSG_ARR']=['Unable to update shipment right now. Please try again.']; header('Location: '.$redirect); exit();
}
$_SESSION['SUCCESSMSG']='Shipment details updated successfully.';
header('Location: dashboard.php'); exit();
