<?php
/**
 * One-time administrator bootstrap using persistent SQLite storage.
 * Set ADMIN_SETUP_KEY to a random secret (32+ characters) before visiting this page.
 * Remove the key after creating the first administrator.
 */
if (session_status() !== PHP_SESSION_ACTIVE) {
    $isHttps = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
    session_set_cookie_params(['httponly' => true, 'secure' => $isHttps, 'samesite' => 'Strict', 'path' => '/']);
    session_start();
}

require_once __DIR__ . '/database/connection.php';

function setup_escape(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

$setupSecret = getenv('ADMIN_SETUP_KEY');
if (($setupSecret === false || trim((string)$setupSecret) === '') && defined('ADMIN_SETUP_KEY')) {
    $setupSecret = (string) ADMIN_SETUP_KEY;
}
if ($setupSecret === false || strlen($setupSecret) < 32) {
    http_response_code(404);
    exit('Not found');
}

try {
    $db = app_db();
} catch (Throwable $exception) {
    // Do not display PHP warnings before headers or disclose internal paths/configuration.
    error_log('Admin setup unavailable: ' . $exception->getMessage());
    http_response_code(503);
    exit('Setup is temporarily unavailable. Check the hosting storage configuration.');
}

$adminExists = static function (PDO $db): bool {
    return (bool)$db->query('SELECT 1 FROM admin LIMIT 1')->fetchColumn();
};

if ($adminExists($db)) {
    http_response_code(410);
    exit('Admin setup is already complete. Use the admin login page.');
}

if (empty($_SESSION['admin_setup_csrf'])) {
    $_SESSION['admin_setup_csrf'] = bin2hex(random_bytes(32));
}

$error = '';
$success = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $providedKey = (string)($_POST['setup_key'] ?? '');
    $csrf = (string)($_POST['csrf_token'] ?? '');
    $username = trim((string)($_POST['username'] ?? ''));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');

    if (!hash_equals($setupSecret, $providedKey)) {
        $error = 'The setup key is invalid.';
    } elseif (empty($_SESSION['admin_setup_csrf']) || !hash_equals($_SESSION['admin_setup_csrf'], $csrf)) {
        $error = 'Your setup session expired. Refresh and try again.';
    } elseif (!preg_match('/^[A-Za-z0-9_.@-]{3,190}$/', $username)) {
        $error = 'Choose a username with 3–190 letters, numbers, dots, underscores, @ or hyphens.';
    } elseif (strlen($password) < 12 || strlen($password) > 4096) {
        $error = 'Use a password between 12 and 4096 characters.';
    } elseif (!hash_equals($password, $confirm)) {
        $error = 'The passwords do not match.';
    } else {
        try {
            $db->beginTransaction();
            // SQLite serializes writers, so the existence check and insert are atomic.
            if ($adminExists($db)) {
                $db->rollBack();
                $error = 'Admin setup is already complete.';
            } else {
                $stmt = $db->prepare('INSERT INTO admin (username, passw) VALUES (:username, :passw)');
                $stmt->execute([':username' => $username, ':passw' => password_hash($password, PASSWORD_DEFAULT)]);
                $db->commit();
                session_regenerate_id(true);
                $_SESSION = [];
                session_destroy();
                $success = true;
            }
        } catch (Throwable $exception) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            error_log('Admin setup insert failed: ' . $exception->getMessage());
            $error = 'Could not create the administrator. The username may already be in use.';
        }
    }
}
?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex,nofollow">
  <title>NEXTGEN Logistics — Admin Setup</title>
  <link rel="stylesheet" href="css/cssreset.css">
  <link rel="stylesheet" href="css/bootstrap.css">
  <link rel="stylesheet" href="css/main.css">
  <style>
    body{min-height:100vh;background:#071b34;color:#eaf2fb;display:grid;place-items:center;padding:24px;font-family:Arial,sans-serif}
    .setup-card{width:min(100%,480px);background:#fff;color:#14253a;border-radius:18px;padding:clamp(24px,5vw,40px);box-shadow:0 24px 80px #0005}
    .setup-card h1{font-size:1.8rem;font-weight:700;margin:0 0 8px}.setup-card p{line-height:1.6}
    .setup-card label{display:block;font-weight:600;margin:16px 0 6px}.setup-card input{width:100%;min-height:46px;padding:10px 12px;border:1px solid #cbd5e1;border-radius:8px}
    .setup-card button{width:100%;margin-top:22px;min-height:48px;background:#1769aa;color:white;border:0;border-radius:8px;font-weight:700}
    .notice{padding:12px;border-radius:8px;margin:16px 0;background:#e8f4ff}.error{background:#fff0f0;color:#922}.muted{color:#53657a;font-size:.93rem}a{color:#1769aa}
  </style>
</head>
<body>
<main class="setup-card">
  <h1>Administrator setup</h1>
  <p class="muted">Create the first NEXTGEN Logistics administrator. This page becomes unavailable after an admin account exists.</p>
  <?php if ($success): ?>
    <div class="notice">Administrator created successfully. Remove <code>ADMIN_SETUP_KEY</code> from Railway variables, then sign in.</div>
    <p><a href="admin/index.php">Go to administrator login</a></p>
  <?php else: ?>
    <?php if ($error !== ''): ?><div class="notice error" role="alert"><?php echo setup_escape($error); ?></div><?php endif; ?>
    <form method="post" action="admin-setup.php" autocomplete="off">
      <input type="hidden" name="csrf_token" value="<?php echo setup_escape((string)$_SESSION['admin_setup_csrf']); ?>">
      <label for="setup_key">Setup key</label><input id="setup_key" name="setup_key" type="password" required autocomplete="off">
      <label for="username">Admin username</label><input id="username" name="username" type="text" minlength="3" maxlength="190" pattern="[A-Za-z0-9_.@-]+" required autocomplete="username">
      <label for="password">Password (at least 12 characters)</label><input id="password" name="password" type="password" minlength="12" maxlength="4096" required autocomplete="new-password">
      <label for="confirm_password">Confirm password</label><input id="confirm_password" name="confirm_password" type="password" minlength="12" maxlength="4096" required autocomplete="new-password">
      <button type="submit">Create administrator</button>
    </form>
  <?php endif; ?>
</main>
</body>
</html>
