<?php
/**
 * Copy this file to private-config.php on the hosting account only.
 * Set a unique random ADMIN_SETUP_KEY before opening admin-setup.php.
 * Do not commit private-config.php. Leave the key empty until you replace it.
 */
if (!defined('ADMIN_SETUP_KEY')) {
    define('ADMIN_SETUP_KEY', '');
}
// Optional: use a writable directory outside htdocs where your host permits it.
// define('SQLITE_PATH', '/absolute/private/path/nextgen.sqlite');
