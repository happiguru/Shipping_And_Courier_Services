<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}

if (empty($_SESSION['USER_NUM'])) {
    header("location: tracking.php");
    exit();
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
    <title>NEXTGEN - Tracking information</title>
  </head>
  <body>
<?php
  require('header.php');
?>
<?php
    $num = (string)$_SESSION['USER_NUM'];
    require_once __DIR__ . '/../database/connection.php';
    try {
        $stmt = app_db()->prepare('SELECT * FROM tracking WHERE tracking_number = :number LIMIT 1');
        $stmt->execute([':number' => $num]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (Throwable $exception) {
        error_log('Tracking details lookup failed: ' . $exception->getMessage());
        http_response_code(503);
        exit('Tracking information is temporarily unavailable.');
    }
    if (!$row) {
        unset($_SESSION['USER_NUM']);
        $_SESSION['ERRMSG_ARR'] = ['We could not find that shipment. Please track it again.'];
        header('Location: tracking.php');
        exit();
    }
    $id = $row['id'];
    $num = $row['tracking_number'];
    $shipdate = $row['shipment_date'];
    $estimate_date = $row['delivery_date'];
    $ship_type = $row['shipment_type'];
    $content = $row['content'];
    $receiver = $row['first_name'] . ' ' . $row['last_name'];
    $source_address = $row['source_city'] . ', ' . $row['source_state'] . ', ' . $row['source_zip'];
    $current_address = $row['current_city'] . ', ' . $row['current_state'] . ', ' . $row['current_zip'];
    $destination_address = $row['destination_city'] . ', ' . $row['destination_state'] . ', ' . $row['destination_zip'];
    $phone = $row['contact_number'];
    $status = $row['parcel_status'];
    $trackingEvents = [];
    try {
        $eventStmt = app_db()->prepare('SELECT status, location, description, event_time FROM tracking_events WHERE tracking_number = :number ORDER BY event_time DESC, id DESC LIMIT 20');
        $eventStmt->execute([':number' => $num]);
        $trackingEvents = $eventStmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (Throwable $exception) {
        error_log('Tracking history lookup failed: ' . get_class($exception));
    }
    function tracking_escape($value) {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
?>

<section class="tracking-details-hero">
  <div class="tracking-details-hero-inner">
    <div><span class="tracking-eyebrow"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> NEXTGEN SHIPMENT TRACKING</span><h1>Shipment overview</h1><p>Track your parcel's progress and review its recorded route details.</p></div>
    <div class="tracking-details-id"><span>TRACKING NUMBER</span><strong id="trackingNumberValue"><?php echo tracking_escape($num); ?></strong><div class="tracking-id-actions"><button type="button" id="copyTrackingNumber" class="tracking-copy-button" aria-label="Copy tracking number"><i class="fa-regular fa-copy" aria-hidden="true"></i> Copy ID</button><a href="tracking.php"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i> Track another</a></div><span id="trackingCopyFeedback" class="tracking-copy-feedback" role="status" aria-live="polite"></span></div>
  </div>
</section>
<div class="container mb-5 tracking-details-page">
    <div class="row">
      <div class="col-12 col-md-6">
        <h3 class="my-4">Parcel Information</h3>
        <div class="table-responsive border">          
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Parcel</th>
                <th>Description</th>
              </tr>
            </thead>
            <tbody>
              <tr class="boldder">
                <td>Tracking Number</td>
                <td><?php echo tracking_escape($num); ?></td>
              </tr>
              <tr class="boldder">
                <td>Ship Date</td>
                <td><?php echo tracking_escape($shipdate); ?></td>
              </tr>
              <tr class="boldder">
                <td>Estimated Delivery Date</td>
                <td><?php echo tracking_escape($estimate_date); ?></td>
              </tr>
              <tr class="boldder">
                <td>Shipment Type</td>
                <td><?php echo tracking_escape($ship_type); ?></td>
              </tr>
              <tr class="boldder">
                <td>Parcel Content</td>
                <td><?php echo tracking_escape($content); ?></td>
              </tr>
              <tr class="boldder">
                <td>Receiver Name</td>
                <td><?php echo tracking_escape($receiver); ?></td>
              </tr>
              <tr class="boldder">
                <td>Source Address</td>
                <td><?php echo tracking_escape($source_address); ?></td>
              </tr>
              <tr class="boldder">
                <td>Current Location</td>
                <td><?php echo tracking_escape($current_address); ?></td>
              </tr>
              <tr class="boldder">
                <td>Destination Address</td>
                <td><?php echo tracking_escape($destination_address); ?></td>
              </tr>
              <tr class="boldder">
                <td>Contact Number</td>
                <td><?php echo tracking_escape($phone); ?></td>
              </tr>
              <tr class="boldder">
                <td>Parcel Status</td>
                <td><?php echo tracking_escape($status); ?></td>
              </tr>
            </tbody>
          </table>
          </div>
      </div>
      <div class="col-12 col-md-6">
        <section class="tracking-progress-card" aria-labelledby="trackingProgressTitle">
          <p class="tracking-progress-kicker">LIVE SHIPMENT OVERVIEW</p>
          <h3 id="trackingProgressTitle">Delivery progress</h3>
          <p class="tracking-progress-number">Tracking ID: <strong><?php echo tracking_escape($num); ?></strong></p>
          <div class="tracking-status-summary"><span class="tracking-status-indicator <?php echo $status === 'Delivered' ? 'is-delivered' : ($status === 'Missing Parcel' ? 'is-attention' : 'is-moving'); ?>"></span><div><span class="tracking-status-label">CURRENT STATUS</span><strong><?php echo tracking_escape($status); ?></strong></div><span class="tracking-status-updated"><i class="fa-regular fa-calendar" aria-hidden="true"></i> Shipment date: <?php echo tracking_escape($shipdate); ?></span></div>
          <?php if ($status === 'On Hold'): ?>
            <div class="tracking-alert" role="status"><i class="fa-solid fa-pause-circle" aria-hidden="true"></i><div><strong>Shipment temporarily on hold</strong><p>Your shipment is on hold. Please contact customer support if you need more information.</p></div></div>
          <?php elseif ($status === 'Missing Parcel'): ?>
            <div class="tracking-alert" role="status"><i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i><div><strong>Shipment requires attention</strong><p>Please contact customer support for the latest information about this parcel.</p></div></div>
          <?php else:
            $trackingStages = ['Packaging' => 'Shipment prepared', 'Shipped' => 'Shipment dispatched', 'Transit' => 'In transit', 'Delivered' => 'Delivered'];
            $stageOrder = ['Packaging', 'Shipped', 'Transit', 'Delivered'];
            $activeStage = array_search($status, $stageOrder, true);
            if ($activeStage === false) $activeStage = 0;
          ?>
            <ol class="tracking-timeline">
              <?php foreach ($stageOrder as $stageIndex => $stageName): $stageDone = $stageIndex <= $activeStage; ?>
                <li class="<?php echo $stageDone ? 'is-complete' : ''; ?> <?php echo $stageIndex === $activeStage ? 'is-current' : ''; ?>">
                  <span class="tracking-timeline-marker" aria-hidden="true"><?php if ($stageDone): ?><i class="fa-solid fa-check"></i><?php else: ?><span><?php echo $stageIndex + 1; ?></span><?php endif; ?></span>
                  <div class="tracking-timeline-copy"><strong><?php echo tracking_escape($trackingStages[$stageName]); ?></strong>
                    <?php if ($stageIndex === 0): ?><span>Shipment information recorded</span>
                    <?php elseif ($stageIndex === 1): ?><span>Parcel has left the origin</span>
                    <?php elseif ($stageIndex === 2): ?><span>Parcel is moving toward its destination</span>
                    <?php else: ?><span>Shipment delivery completed</span><?php endif; ?>
                  </div>
                  <?php if ($stageIndex === $activeStage): ?><span class="tracking-current-badge">Current</span><?php endif; ?>
                </li>
              <?php endforeach; ?>
            </ol>
          <?php endif; ?>
          <?php if ($trackingEvents): ?>
            <section class="tracking-history" aria-labelledby="trackingHistoryTitle" style="margin-top:24px;padding:20px;border:1px solid #e5ebf4;border-radius:14px;background:#fff">
              <h3 id="trackingHistoryTitle" style="margin:0 0 14px;font-size:18px">Tracking history</h3>
              <ol style="list-style:none;margin:0;padding:0">
                <?php foreach ($trackingEvents as $event): ?>
                  <li style="padding:12px 0;border-top:1px solid #edf1f6">
                    <strong><?php echo tracking_escape($event['status']); ?></strong>
                    <?php if ((string)$event['location'] !== ''): ?><span> · <?php echo tracking_escape($event['location']); ?></span><?php endif; ?>
                    <?php if ((string)$event['description'] !== ''): ?><p style="margin:5px 0;color:#64748b"><?php echo tracking_escape($event['description']); ?></p><?php endif; ?>
                    <time style="font-size:12px;color:#64748b"><?php echo tracking_escape($event['event_time']); ?></time>
                  </li>
                <?php endforeach; ?>
              </ol>
            </section>
          <?php endif; ?>
          <div class="tracking-route-summary">
            <div><span>ORIGIN</span><strong><?php echo tracking_escape($row['source_city']); ?></strong><small><?php echo tracking_escape(trim($row['source_state'] . ' ' . $row['source_zip'])); ?></small></div>
            <i class="fa-solid fa-arrow-right-long" aria-hidden="true"></i>
            <div><span>DESTINATION</span><strong><?php echo tracking_escape($row['destination_city']); ?></strong><small><?php echo tracking_escape(trim($row['destination_state'] . ' ' . $row['destination_zip'])); ?></small></div>
          </div>
          <div class="tracking-delivery-date"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i><div><span>Estimated delivery</span><strong><?php echo tracking_escape($estimate_date); ?></strong></div></div>
        </section>
      </div>
      <div class="col-12"><section class="shipment-map-card shipment-map-card-wide" aria-labelledby="shipmentMapTitle">
<?php
$mapOrigin = trim($row['source_city'] . ', ' . $row['source_state'] . ' ' . $row['source_zip']);
$mapDestination = trim($row['destination_city'] . ', ' . $row['destination_state'] . ' ' . $row['destination_zip']);
$mapCurrent = trim($row['current_city'] . ', ' . $row['current_state'] . ' ' . $row['current_zip']);
$mapQuery = http_build_query(['saddr' => $mapOrigin, 'daddr' => $mapDestination, 'output' => 'embed', 't' => 'm']);
$mapUrl = 'https://maps.google.com/maps?' . $mapQuery;
$mapSatelliteUrl = 'https://maps.google.com/maps?' . http_build_query(['saddr' => $mapOrigin, 'daddr' => $mapDestination, 'output' => 'embed', 't' => 'k']);
?>
<div class="shipment-map-heading"><div><p class="shipment-map-kicker">SHIPMENT ROUTE</p><h3 id="shipmentMapTitle">Track your parcel on the map</h3><p>Google Maps route overview based on shipment locations recorded by NEXTGEN.</p></div>
<div class="shipment-map-controls" role="group" aria-label="Map layer"><button type="button" class="is-active" id="shipmentStreetLayer" data-map-url="<?php echo tracking_escape($mapUrl); ?>">Street</button><button type="button" id="shipmentSatelliteLayer" data-map-url="<?php echo tracking_escape($mapSatelliteUrl); ?>">Satellite</button></div></div>
<iframe id="shipmentTrackingMap" class="shipment-tracking-map" title="Google Maps shipment route from origin to destination" src="<?php echo tracking_escape($mapUrl); ?>" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe>
<div class="shipment-map-legend"><span><i class="shipment-map-dot shipment-origin-dot"></i>Origin: <?php echo tracking_escape($mapOrigin); ?></span><span><i class="shipment-map-dot shipment-current-dot"></i>Recorded current location: <?php echo tracking_escape($mapCurrent); ?></span><span><i class="shipment-map-dot shipment-destination-dot"></i>Destination: <?php echo tracking_escape($mapDestination); ?></span></div>
<p class="shipment-map-note" id="shipmentMapStatus" role="status">Showing the saved shipment route in Google Maps.</p>
<p class="shipment-map-disclaimer">The route uses saved shipment addresses and is not live GPS tracking. The recorded current location is shown from shipment records; live courier positioning requires connected GPS coordinates.</p></section>
            <section class="tracking-again-card" aria-labelledby="trackAnotherTitle">
              <span class="tracking-search-kicker">ANOTHER SHIPMENT?</span>
              <h3 id="trackAnotherTitle">Track another parcel</h3>
              <p>Have another tracking number? Enter it below to check a different shipment.</p>
              <?php
                if(isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
                  echo '<div class="tracking-form-error" role="alert">';
                  foreach($_SESSION['ERRMSG_ARR'] as $msg) echo '<p>' . tracking_escape($msg) . '</p>';
                  echo '</div>';
                  unset($_SESSION['ERRMSG_ARR']);
                }
              ?>
              <form action="../controllers/tracking_exec.php" method="POST" name="trackingAgain" class="tracking-search-form">
                <label for="trackingNumberAgain">Tracking number</label>
                <div class="tracking-input-wrap"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><input type="text" name="trackingnumber" id="trackingNumberAgain" maxlength="100" pattern="[A-Za-z0-9_-]+" autocomplete="off" placeholder="Enter tracking ID" required></div>
                <button type="submit">Track another shipment <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
              </form>
            </section>
      </div>
    </div>
</div>
<script>
(function(){
  const mapFrame = document.getElementById('shipmentTrackingMap');
  const streetButton = document.getElementById('shipmentStreetLayer');
  const satelliteButton = document.getElementById('shipmentSatelliteLayer');
  if (!mapFrame || !streetButton || !satelliteButton) return;
  [streetButton, satelliteButton].forEach(function(button) {
    button.addEventListener('click', function() {
      mapFrame.src = this.getAttribute('data-map-url');
      streetButton.classList.toggle('is-active', this === streetButton);
      satelliteButton.classList.toggle('is-active', this === satelliteButton);
    });
  });
})();
</script>
<script>
(function(){
  const button = document.getElementById('copyTrackingNumber');
  const value = document.getElementById('trackingNumberValue');
  const feedback = document.getElementById('trackingCopyFeedback');
  if (!button || !value || !feedback) return;
  button.addEventListener('click', async function(){
    const text = value.textContent.trim();
    try {
      if (navigator.clipboard && window.isSecureContext) {
        await navigator.clipboard.writeText(text);
      } else {
        const helper = document.createElement('textarea');
        helper.value = text; helper.setAttribute('readonly','');
        helper.style.position = 'fixed'; helper.style.opacity = '0';
        document.body.appendChild(helper); helper.select();
        const copied = document.execCommand('copy'); helper.remove();
        if (!copied) throw new Error('Copy unavailable');
      }
      feedback.textContent = 'Tracking number copied.';
    } catch (error) {
      feedback.textContent = 'Please select and copy the tracking number above.';
    }
  });
})();
</script>

<?php require_once('footer.php'); ?>