<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'rest_bar');
define('DB_USER', 'baruser');
define('DB_PASS', 'baruser');

// Application configuration
define('APP_NAME', 'RestBar');
define('APP_URL', 'http://localhost/restbar');

// Session configuration
define('SESSION_LIFETIME', 3600); // 1 hour
define('SESSION_NAME', 'restbar_session');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('America/Mexico_City');