<?php
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
        session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Lax', 'path' => '/']);
        session_start();
    }
    unset($_SESSION['SESS_MEMBER_ID']);
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
    <title>NEXTGEN - Packing Tracking</title>
  </head>
  <body>
<?php
    require('header.php');
?>
<main class="tracking-page">
  <section class="tracking-hero">
    <div class="tracking-hero-grid">
      <div class="tracking-hero-copy">
        <span class="tracking-eyebrow"><i class="fa-solid fa-location-dot" aria-hidden="true"></i> NEXTGEN SHIPMENT TRACKING</span>
        <h1>Every delivery.<br><span>One clear view.</span></h1>
        <p>Follow your shipment from dispatch to destination. Enter your tracking number to see the latest recorded status and route information.</p>
        <div class="tracking-trust-row"><span><i class="fa-solid fa-shield-halved" aria-hidden="true"></i> Secure tracking</span><span><i class="fa-solid fa-route" aria-hidden="true"></i> Route overview</span><span><i class="fa-regular fa-clock" aria-hidden="true"></i> Status updates</span></div>
      </div>
      <div class="tracking-search-card">
        <div class="tracking-search-icon"><i class="fa-solid fa-box-open" aria-hidden="true"></i></div>
        <p class="tracking-search-kicker">SHIPMENT LOOKUP</p>
        <h2>Track your parcel</h2>
        <p class="tracking-search-description">Your tracking number can be found in your shipment confirmation.</p>
        <?php
          if(isset($_SESSION['ERRMSG_ARR']) && is_array($_SESSION['ERRMSG_ARR']) && count($_SESSION['ERRMSG_ARR']) > 0) {
            echo '<div class="tracking-form-error" role="alert">';
            foreach($_SESSION['ERRMSG_ARR'] as $msg) {
              echo '<p>' . htmlspecialchars((string)$msg, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</p>';
            }
            echo '</div>';
            unset($_SESSION['ERRMSG_ARR']);
          }
        ?>
        <form action="../controllers/tracking_exec.php" method="POST" name="tracking" class="tracking-search-form">
          <label for="trackingNumber">Tracking number</label>
          <div class="tracking-input-wrap"><i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i><input type="text" maxlength="100" pattern="[A-Za-z0-9_-]+" autocomplete="off" name="trackingnumber" id="trackingNumber" placeholder="e.g. NG261014A3F72C91B4" required></div>
          <button type="submit">Track shipment <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
        </form>
        <div class="tracking-privacy-note"><i class="fa-solid fa-lock" aria-hidden="true"></i> Enter your unique tracking ID to view shipment details.</div>
      </div>
    </div>
    <div class="tracking-hero-bottom"><span>FROM PICKUP</span><div class="tracking-route-line"><i class="fa-solid fa-circle"></i><span></span><i class="fa-solid fa-location-dot"></i></div><span>TO DELIVERY</span></div>
  </section>
  <section class="tracking-benefits" aria-label="Tracking features">
    <article><span class="tracking-benefit-icon"><i class="fa-solid fa-box" aria-hidden="true"></i></span><div><h3>Shipment details</h3><p>Review key parcel information in one place.</p></div></article>
    <article><span class="tracking-benefit-icon"><i class="fa-solid fa-route" aria-hidden="true"></i></span><div><h3>Route visibility</h3><p>See origin and destination on the map.</p></div></article>
    <article><span class="tracking-benefit-icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></span><div><h3>Need assistance?</h3><p>Our team can help with shipment questions.</p></div></article>
  </section>
  <section class="tracking-promo">
    <div><span class="tracking-eyebrow">DELIVERY, MADE CLEAR</span><h2>Moving your world forward.</h2><p>From pickup to the final destination, NEXTGEN helps you stay informed along the way.</p></div>
    <img src="../assets/images/tracking/track1.jpg" alt="NEXTGEN shipment transportation and delivery">
  </section>
</main>
<?php
    include_once("map.php");
    require_once('footer.php')
?>