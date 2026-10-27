<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || strtolower((string)($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '')) === 'https';
    session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Lax', 'path' => '/']);
    session_start();
}
if (empty($_SESSION['contact_csrf_token'])) $_SESSION['contact_csrf_token'] = bin2hex(random_bytes(32));
$contactStatus = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact_submit'])) {
    $token = (string)($_POST['csrf_token'] ?? '');
    $name = trim((string)($_POST['name'] ?? ''));
    $email = trim((string)($_POST['email'] ?? ''));
    $subject = trim((string)($_POST['subject'] ?? ''));
    $message = trim((string)($_POST['message'] ?? ''));
    if (!hash_equals((string)$_SESSION['contact_csrf_token'], $token)) {
        $contactStatus = 'Your session could not be verified. Please reload and try again.';
    } elseif ($name === '' || strlen($name) > 190 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254 || strlen($subject) > 190 || $message === '' || strlen($message) > 5000) {
        $contactStatus = 'Please enter a valid name, email address, and message.';
    } else {
        try {
            require_once __DIR__ . '/../database/connection.php';
            $stmt = app_db()->prepare('INSERT INTO contact_messages (name,email,subject,message) VALUES (:name,:email,:subject,:message)');
            $stmt->execute([':name'=>$name, ':email'=>$email, ':subject'=>$subject, ':message'=>$message]);
            $contactStatus = 'Your message has been received. Our team will review it.';
            $_SESSION['contact_csrf_token'] = bin2hex(random_bytes(32));
        } catch (Throwable $exception) {
            error_log('Contact form submission failed: ' . get_class($exception));
            $contactStatus = 'We could not save your message right now. Please try again later.';
        }
    }
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
    <title>NEXTGEN - Contact Us</title>
  </head>
  <body>

<?php
    require('header.php');
?>
<section class="page-title-secttion">
    <div class="container">
      <div class="page-header">
        <h1>Contact</h1>
      </div>
      <ol class="breadcrumb justify-content-center">
        <li><a href="../index.php">Home</a></li>
        <li class="active">/ Contact</li>
      </ol>
    </div>
</section>
<?php
    include_once("map.php");
?>
<section class="contact-wrap section-padding">
  <div class="container">
    <div class="section-heading">
      <h2 class="section-title">How can we help?</h2>
      <p>Choose the next step that best matches your shipment. Service availability and delivery estimates are confirmed for each route.</p>
    </div>
    <div class="row">
      <div class="col-12 col-md-4 mb-4">
        <article class="office-address h-100">
          <h3>Request a quote</h3>
          <p>Share your pickup and delivery locations, shipment details and preferred dates so the team can review your request.</p>
          <a class="btn btn-primary" href="quotation.php">Get a shipping quote</a>
        </article>
      </div>
      <div class="col-12 col-md-4 mb-4">
        <article class="office-address h-100">
          <h3>Track a shipment</h3>
          <p>Use the tracking number provided for your shipment to view its available tracking details.</p>
          <a class="btn btn-primary" href="tracking.php">Track shipment</a>
        </article>
      </div>
      <div class="col-12 col-md-4 mb-4">
        <article class="office-address h-100">
          <h3>Explore services</h3>
          <p>Review available freight options and contact the team to confirm what is supported for your route.</p>
          <a class="btn btn-primary" href="services.php">View services</a>
        </article>
      </div>
    </div>
  </div>
</section>
<section class="contact-wrap section-padding">
  <div class="container">
    <div class="section-heading">
      <h2 class="section-title">Send us a message</h2>
      <p>Share your question with the NEXTGEN Logistics team.</p>
    </div>
    <?php if ($contactStatus !== ''): ?><div class="alert alert-info" role="status"><?php echo htmlspecialchars($contactStatus, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?></div><?php endif; ?>
    <form method="post" action="contact.php" class="contact-message-form" style="max-width:760px;margin:0 auto">
      <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['contact_csrf_token'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); ?>">
      <div class="form-row">
        <div class="col-12 col-md-6 mb-3"><label for="contactName">Name</label><input class="form-control" type="text" id="contactName" name="name" maxlength="190" autocomplete="name" required></div>
        <div class="col-12 col-md-6 mb-3"><label for="contactEmail">Email</label><input class="form-control" type="email" id="contactEmail" name="email" maxlength="254" autocomplete="email" required></div>
      </div>
      <div class="mb-3"><label for="contactSubject">Subject (optional)</label><input class="form-control" type="text" id="contactSubject" name="subject" maxlength="190"></div>
      <div class="mb-3"><label for="contactMessage">Message</label><textarea class="form-control" id="contactMessage" name="message" rows="5" maxlength="5000" required></textarea></div>
      <button class="btn btn-primary" type="submit" name="contact_submit" value="1">Send message</button>
    </form>
  </div>
</section>
<?php
    require_once('footer.php')
?>