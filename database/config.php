<?php
/**
 * SQLite storage configuration.
 * Default storage is protected htdocs/storage. Prefer a writable directory
 * outside the document root by setting SQLITE_PATH when the host permits it.
 */
$privateConfig = __DIR__ . '/private-config.php';
if (is_file($privateConfig)) {
    require_once $privateConfig;
}

if (!defined('SQLITE_PATH')) {
    $configuredPath = getenv('SQLITE_PATH');
    $defaultPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'nextgen.sqlite';
    define('SQLITE_PATH', ($configuredPath !== false && trim($configuredPath) !== '') ? trim($configuredPath) : $defaultPath);
}
