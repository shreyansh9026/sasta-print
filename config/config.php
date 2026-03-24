<?php
// ── Application Configuration ──────────────────────────────────────────────────
// Load .env first
require_once __DIR__ . '/Env.php';
Env::load(dirname(__DIR__) . '/.env');

define('APP_NAME',  Env::get('APP_NAME',  'SastaPrint'));
define('APP_ENV',   Env::get('APP_ENV',   'production'));
define('APP_DEBUG', Env::get('APP_DEBUG', 'false') === 'true');
define('BASE_URL',  rtrim(Env::get('BASE_URL', 'http://localhost/sasta%20print/public'), '/'));

define('DB_HOST',   Env::get('DB_HOST', 'localhost'));
define('DB_PORT',   Env::get('DB_PORT', '3306'));
define('DB_USER',   Env::get('DB_USER', 'root'));
define('DB_PASS',   Env::get('DB_PASS', ''));
define('DB_NAME',   Env::get('DB_NAME', 'print_service'));

define('MAIL_HOST',       Env::get('MAIL_HOST',       'smtp.gmail.com'));
define('MAIL_PORT',       (int) Env::get('MAIL_PORT',  '587'));
define('MAIL_USERNAME',   Env::get('MAIL_USERNAME',   ''));
define('MAIL_PASSWORD',   Env::get('MAIL_PASSWORD',   ''));
define('MAIL_FROM_NAME',  Env::get('MAIL_FROM_NAME',  APP_NAME));
define('MAIL_FROM_EMAIL', Env::get('MAIL_FROM_EMAIL', 'noreply@sastaprint.com'));

define('RAZORPAY_KEY_ID',     Env::get('RAZORPAY_KEY_ID',     ''));
define('RAZORPAY_KEY_SECRET', Env::get('RAZORPAY_KEY_SECRET', ''));

define('CACHE_ENABLED', Env::get('CACHE_ENABLED', 'true') === 'true');
define('CACHE_TTL',     (int) Env::get('CACHE_TTL', '3600'));

define('STORAGE_PATH', dirname(__DIR__) . '/storage');
define('UPLOAD_PATH',  dirname(__DIR__) . '/storage/uploads');

// PHP error settings based on environment
if (APP_DEBUG) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
}
