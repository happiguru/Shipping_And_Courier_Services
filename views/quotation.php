<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}
if (empty($_SESSION['quote_csrf_token'])) {
    $_SESSION['quote_csrf_token'] = bin2hex(random_bytes(32));
}
$quoteStatus = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) &&
    isset($_POST['csrf_token']) && is_string($_POST['csrf_token']) &&
    hash_equals($_SESSION['quote_csrf_token'], $_POST['csrf_token'])) {
    $fields = [
        'firstname' => 'First name', 'lastname' => 'Last name', 'email' => 'Email',
        'contactnumber' => 'Contact number', 'paddress' => 'Pickup address',
        'daddress' => 'Delivery address', 'pdate' => 'Pickup date', 'ptime' => 'Pickup time',
        'ddate' => 'Expected delivery date', 'pcity' => 'Pickup city', 'pstate' => 'Pickup state',
        'pcountry' => 'Pickup country', 'dcity' => 'Delivery city', 'dstate' => 'Delivery state',
        'dcountry' => 'Delivery country', 'receivername' => 'Receiver name',
        'shipmentType' => 'Shipment type', 'pickupType' => 'Pickup vehicle', 'content' => 'Parcel contents'
    ];
    $values = [];
    $maxLengths = [
        'firstname' => 100, 'lastname' => 100, 'email' => 254, 'contactnumber' => 60,
        'paddress' => 500, 'daddress' => 500, 'pdate' => 10, 'ptime' => 5, 'ddate' => 10,
        'pcity' => 120, 'pstate' => 120, 'pcountry' => 120, 'dcity' => 120,
        'dstate' => 120, 'dcountry' => 120, 'receivername' => 200,
        'shipmentType' => 40, 'pickupType' => 40, 'content' => 2000
    ];
    $tooLong = false;
    foreach ($fields as $key => $label) {
        $value = isset($_POST[$key]) && is_string($_POST[$key]) ? trim(strip_tags($_POST[$key])) : '';
        $value = str_replace(["\r", "\n"], ' ', $value);
        if (strlen($value) > $maxLengths[$key]) {
            $tooLong = true;
        }
        $values[$key] = $value;
    }
    $requiredKeys = [
        'firstname', 'lastname', 'email', 'contactnumber', 'paddress', 'daddress',
        'pdate', 'ptime', 'ddate', 'pcity', 'pstate', 'dcity', 'dstate',
        'receivername', 'shipmentType', 'pickupType'
    ];
    $missingRequired = false;
    foreach ($requiredKeys as $key) {
        if (($values[$key] ?? '') === '') {
            $missingRequired = true;
        }
    }
    $pickupDate = DateTime::createFromFormat('!Y-m-d', $values['pdate']);
    $deliveryDate = DateTime::createFromFormat('!Y-m-d', $values['ddate']);
    $validPickupDate = $pickupDate && $pickupDate->format('Y-m-d') === $values['pdate'];
    $validDeliveryDate = $deliveryDate && $deliveryDate->format('Y-m-d') === $values['ddate'];
    $validTime = (bool)preg_match('/^(?:[01]\\d|2[0-3]):[0-5]\\d$/', $values['ptime']);
    $validShipmentType = in_array($values['shipmentType'], ['Air Freight', 'Marine Freight', 'Land Freight'], true);
    $validPickupType = in_array($values['pickupType'], ['Bike', 'Van', 'Truck'], true);
    if ($tooLong) {
        $quoteStatus = 'One or more fields exceed the allowed length. Please shorten the details and try again.';
    } elseif ($missingRequired || !filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
        $quoteStatus = 'Please complete all required fields and provide a valid email address.';
    } elseif (!$validPickupDate || !$validDeliveryDate || !$validTime) {
        $quoteStatus = 'Enter valid pickup and expected delivery dates and a valid pickup time.';
    } elseif (!$validShipmentType || !$validPickupType) {
        $quoteStatus = 'Choose a valid shipment type and pickup vehicle.';
    } elseif ($values['ddate'] < $values['pdate']) {
        $quoteStatus = 'Expected delivery date cannot be earlier than pickup date.';
    } else {
        // Persist the validated request first so it is not lost if shared-host mail is unavailable.
        try {
            require_once __DIR__ . '/../database/connection.php';
            $quoteDb = app_db();
            $quoteInsert = $quoteDb->prepare('INSERT INTO quote_requests (first_name,last_name,email,contact_number,pickup_address,delivery_address,pickup_date,pickup_time,expected_delivery_date,pickup_city,pickup_state,pickup_country,delivery_city,delivery_state,delivery_country,receiver_name,shipment_type,pickup_type,content) VALUES (:first_name,:last_name,:email,:contact_number,:pickup_address,:delivery_address,:pickup_date,:pickup_time,:expected_delivery_date,:pickup_city,:pickup_state,:pickup_country,:delivery_city,:delivery_state,:delivery_country,:receiver_name,:shipment_type,:pickup_type,:content)');
            $quoteInsert->execute([
                ':first_name'=>$values['firstname'], ':last_name'=>$values['lastname'], ':email'=>$values['email'],
                ':contact_number'=>$values['contactnumber'], ':pickup_address'=>$values['paddress'], ':delivery_address'=>$values['daddress'],
                ':pickup_date'=>$values['pdate'], ':pickup_time'=>$values['ptime'], ':expected_delivery_date'=>$values['ddate'],
                ':pickup_city'=>$values['pcity'], ':pickup_state'=>$values['pstate'], ':pickup_country'=>$values['pcountry'],
                ':delivery_city'=>$values['dcity'], ':delivery_state'=>$values['dstate'], ':delivery_country'=>$values['dcountry'],
                ':receiver_name'=>$values['receivername'], ':shipment_type'=>$values['shipmentType'], ':pickup_type'=>$values['pickupType'], ':content'=>$values['content']
            ]);
        } catch (Throwable $exception) {
            error_log('Quote request could not be stored: ' . get_class($exception));
            $quoteStatus = 'We could not save your request right now. Please try again later.';
        }
        $recipient = getenv('QUOTE_RECIPIENT_EMAIL') ?: '';
        $from = getenv('QUOTE_FROM_EMAIL') ?: '';
        if ($quoteStatus !== '') {
            // Keep the database error message and avoid reporting success.
        } elseif (!filter_var($recipient, FILTER_VALIDATE_EMAIL) || !filter_var($from, FILTER_VALIDATE_EMAIL)) {
            $quoteStatus = 'Your request has been saved, but email notification is not configured. Our team can review it.';
            error_log('Quote request email is not configured; set QUOTE_RECIPIENT_EMAIL and QUOTE_FROM_EMAIL.');
        } else {
            $lines = [];
            foreach ($fields as $key => $label) {
                $lines[] = $label . ': ' . ($values[$key] !== '' ? $values[$key] : 'Not provided');
            }
            $headers = "From: " . $from . "\r\n" . "Reply-To: " . $values['email'] . "\r\n" . "Content-Type: text/plain; charset=UTF-8";
            if (mail($recipient, 'NEXTGEN shipping quote request', implode("\n", $lines), $headers)) {
                $quoteStatus = 'Your quote request was sent successfully. Our team will review the details.';
            } else {
                $quoteStatus = 'Your request was saved, but email notification could not be sent. Our team can still review it.';
                error_log('NEXTGEN quote request email could not be sent.');
            }
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit']) &&
    (!isset($_POST['csrf_token']) || !is_string($_POST['csrf_token']) ||
    !hash_equals($_SESSION['quote_csrf_token'], $_POST['csrf_token']))) {
    $quoteStatus = 'Your session could not be verified. Please reload the page and try again.';
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon and touch icons -->
    <link rel="shortcut icon" href="assets/ico/favicon.png">
    <!-- Bootstrap CSS -->
    <link href="../css/cssreset.css" rel="stylesheet" />
    <link href="../css/bootstrap.css" rel="stylesheet" />
    <link href="../css/main.css" rel="stylesheet" />
    <script src="https://kit.fontawesome.com/cbdf2b0f0e.js" crossorigin="anonymous"></script>
    <title>NEXTGEN - Ask A Quote</title>
  </head>
  <body>
<?php
    require('header.php');
?>
<section class="page-title-secttion">
    <div class="container">
      <div class="page-header">
        <h1>Request a Quote</h1>
      </div>
      <ol class="breadcrumb justify-content-center">
        <li><a href="../index.php">Home</a></li>
        <li class="active">/ Quote</li>
      </ol>
    </div>
</section>
<div class="container my-4">
  <div class="row">
  <div class="col-12 col-md-9">
    <h2>Request a quote!</h2>
    <?php if ($quoteStatus !== '') : ?><div class="alert alert-info" role="status"><?php echo htmlspecialchars($quoteStatus, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div><?php endif; ?>
    <div class="">
    <form method="POST" action="quotation.php">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['quote_csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault01">First name</label>
          <input type="text" name ="firstname" class="form-control" id="validationDefault01" placeholder="Mark" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault02">Last name</label>
          <input type="text" name ="lastname" class="form-control" id="validationDefault02" placeholder="Otto" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3">
          <label>Email Address</label>
          <input type="email" name ="email" class="form-control" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label>Contact Number</label>
          <input type="text" name ="contactnumber" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault01">Pickup Address</label>
          <input type="text" name ="paddress" class="form-control" id="validationDefault01" placeholder="Oslo, 12 Street" required>
        </div>
        <div class="col-12 col-md-6 mb-3">
          <label for="validationDefault02">Delivery Address</label>
          <input type="text" name ="daddress" class="form-control" id="validationDefault02" placeholder="Port Harcort" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault01">Pick Up Date</label>
          <input type="date" name ="pdate" class="form-control" required>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault01">Pick Up Time</label>
          <input type="time" name ="ptime" class="form-control" required>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault02">Expected Delivery Date</label>
          <input type="date" name ="ddate" class="form-control" required>
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-3">
          <label for="validationDefault03">Pick Up City</label>
          <input type="text" name ="pcity" class="form-control" id="validationDefault03" placeholder="City" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault04">Pick Up State</label>
          <input type="text" name ="pstate" class="form-control" id="validationDefault04" placeholder="State" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault05">Pick Up Country</label>
          <input type="text" name ="pcountry" class="form-control" id="validationDefault05" placeholder="Country">
        </div>
      </div>
      <div class="form-row">
        <div class="col-md-6 mb-3">
          <label for="validationDefault03">Destination City</label>
          <input type="text" name ="dcity" class="form-control" id="validationDefault03" placeholder="City" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault04">Destination State</label>
          <input type="text" name ="dstate" class="form-control" id="validationDefault04" placeholder="State" required>
        </div>
        <div class="col-md-3 mb-3">
          <label for="validationDefault05">Destination Country</label>
          <input type="text" name ="dcountry" class="form-control" id="validationDefault05" placeholder="Country">
        </div>
      </div>
      <div class="form-row">
        <div class="col-12 col-md-4 mb-3">
          <label for="validationDefault01">Receiver's Name</label>
          <input type="text" name ="receivername" class="form-control" id="validationDefault01" placeholder="John Doe" required>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label class="mr-sm-2" for="shipmentType">Shipment Type</label>
          <select class="custom-select mr-sm-2" name ="shipmentType" id="shipmentType" required>
            <option value="" selected disabled>Choose...</option>
            <option value="Air Freight">Air Freight</option>
            <option value="Marine Freight">Shipping</option>
            <option value="Land Freight">Land freight</option>
          </select>
        </div>
        <div class="col-12 col-md-4 mb-3">
          <label class="mr-sm-2" for="inlineFormCustomSelect">Pick Up Vehicle Type</label>
          <select class="custom-select mr-sm-2" name ="pickupType" id="pickupVehicleType" required>
            <option value="" selected disabled>Choose...</option>
            <option value="Bike">Bike</option>
            <option value="Van">Van</option>
            <option value="Truck">Truck</option>
          </select>
        </div>
      </div>
      <div class="form-row">
        <label for="exampleFormControlTextarea1">Parcel Content</label>
        <textarea class="form-control" name ="content" id="exampleFormControlTextarea1" rows="3"></textarea>
      </div>
      <div class="form-row mt-3">
        <input class="btn btn-primary" name="submit" type="submit" value="Submit Form"/>
      </div>
    </form>
    </div>
  </div>
  </div>
</div>
<?php
    include_once("map.php");
    require_once('footer.php')
?>