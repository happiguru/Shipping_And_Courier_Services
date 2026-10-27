<?php
/**
 * Backwards-compatible configuration shim.
 * The application now uses SQLite via database/config.php; no database
 * credentials should be stored in source control.
 */
require_once __DIR__ . '/config.php';
