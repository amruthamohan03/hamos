<?php
/**
 * Main Application Configuration
 */

// Define paths first (needed by other configs)
define('APP_ROOT', dirname(dirname(__FILE__)));
define('PUBLIC_PATH', APP_ROOT . '/public');
define('BASE_URL', 'http://localhost/sms/public');
define('VIEW_PATH',APP_ROOT.'\app\views');
define('URL_ROOT', '/sms/public');

// Load database configuration
$dbConfig = require_once APP_ROOT . '/config/database.php';

// Store database config in a constant for easy access
define('DB_CONFIG', $dbConfig);

// App Configuration
define('APP_NAME', 'Hamos');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, staging, production

// URL Configuration
// For localhost: '/my-mvc-app' or '' if using virtual host
// For production: '' (empty string)
define('URL_SUBFOLDER', '');

// Database Configuration (from database.php)
define('DB_CONNECTION', $dbConfig['default']); // 'mysql', 'pgsql', 'sqlite'
define('DB_HOST', $dbConfig['connections'][$dbConfig['default']]['host']);
define('DB_PORT', $dbConfig['connections'][$dbConfig['default']]['port'] ?? 3306);
define('DB_NAME', $dbConfig['connections'][$dbConfig['default']]['database']);
define('DB_USER', $dbConfig['connections'][$dbConfig['default']]['username']);
define('DB_PASS', $dbConfig['connections'][$dbConfig['default']]['password']);
define('DB_CHARSET', $dbConfig['connections'][$dbConfig['default']]['charset'] ?? 'utf8mb4');

// Session Configuration
define('SESSION_NAME', 'mvc_session');
define('SESSION_LIFETIME', 7200); // 2 hours in seconds
define('SESSION_PATH', '/');
define('SESSION_DOMAIN', '');
define('SESSION_SECURE', false); // Set to true if using HTTPS
define('SESSION_HTTPONLY', true);

// Security Configuration
define('HASH_ALGO', PASSWORD_DEFAULT);
define('HASH_COST', 12);
define('CSRF_TOKEN_NAME', 'csrf_token');
define('CSRF_TOKEN_LENGTH', 32);

// File Upload Configuration
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('MAX_FILE_SIZE', 5242880); // 5MB in bytes
define('ALLOWED_FILE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx']);

// Email Configuration (for mail functionality)
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'your-email@gmail.com');
define('MAIL_PASSWORD', 'your-app-password');
define('MAIL_FROM_ADDRESS', 'noreply@example.com');
define('MAIL_FROM_NAME', APP_NAME);

// Pagination
define('ITEMS_PER_PAGE', 10);

// Cache Configuration
define('CACHE_ENABLED', false);
define('CACHE_LIFETIME', 3600); // 1 hour

// Logging
define('LOG_PATH', APP_ROOT . '/logs');
define('LOG_FILE', LOG_PATH . '/app.log');
define('LOG_LEVEL', 'debug'); // debug, info, warning, error

// Environment-based Configuration
switch (APP_ENV) {
    case 'production':
        // Production settings
        error_reporting(0);
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', LOG_PATH . '/error.log');
        define('DEBUG_MODE', false);
        break;
        
    case 'staging':
        // Staging settings
        error_reporting(E_ALL & ~E_NOTICE);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        ini_set('error_log', LOG_PATH . '/staging-error.log');
        define('DEBUG_MODE', true);
        break;
        
    case 'development':
    default:
        // Development settings
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        ini_set('log_errors', 1);
        ini_set('error_log', LOG_PATH . '/dev-error.log');
        define('DEBUG_MODE', true);
        break;
}

// Timezone
date_default_timezone_set('Asia/Kolkata');

// Character Encoding
mb_internal_encoding('UTF-8');
mb_http_output('UTF-8');

// Session Configuration
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', SESSION_LIFETIME);
    ini_set('session.cookie_path', SESSION_PATH);
    ini_set('session.cookie_domain', SESSION_DOMAIN);
    ini_set('session.cookie_secure', SESSION_SECURE);
    ini_set('session.cookie_httponly', SESSION_HTTPONLY);
    ini_set('session.use_strict_mode', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.name', SESSION_NAME);
}

// Create required directories if they don't exist
$requiredDirs = [
    LOG_PATH,
    UPLOAD_PATH,
    APP_ROOT . '/cache'
];

foreach ($requiredDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}