<?php
/**
 * Application Bootstrap — public/index.php
 * Single entry point for all requests.
 */

// ── 1. Session ─────────────────────────────────────────────────────────────────
session_start();

// ── 2. Config & Helpers ────────────────────────────────────────────────────────
require_once '../config/config.php';
require_once '../config/database.php';

// ── 3. Core services / middleware (manually loaded before autoloader) ──────────
require_once '../app/exceptions/AppExceptions.php';
require_once '../app/services/Logger.php';
require_once '../app/middleware/CsrfMiddleware.php';
require_once '../app/middleware/AuthMiddleware.php';
require_once '../app/middleware/RateLimitMiddleware.php';
require_once '../app/middleware/Validator.php';

// ── 4. Composer autoloader (if vendor exists) ──────────────────────────────────
if (file_exists('../vendor/autoload.php')) {
    require_once '../vendor/autoload.php';
}

// ── 5. App autoloader (controllers, models, services) ─────────────────────────
spl_autoload_register(function (string $class): void {
    $paths = [
        '../app/controllers/' . $class . '.php',
        '../app/models/'      . $class . '.php',
        '../app/'             . $class . '.php',
        '../app/services/'    . $class . '.php',
    ];
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// ── 6. Global CSRF verification on every POST ──────────────────────────────────
// Skip CSRF for API routes (they use API key / JSON body verification)
$requestUri = $_GET['url'] ?? '/';
$isApiRoute = str_starts_with('/' . ltrim($requestUri, '/'), '/api/');
if (!$isApiRoute) {
    CsrfMiddleware::verify();
}

// ── 7. Global exception handler ────────────────────────────────────────────────
set_exception_handler(function (Throwable $e) {
    Logger::error($e->getMessage(), ['file' => $e->getFile(), 'line' => $e->getLine()]);
    if (APP_DEBUG) {
        echo '<pre style="background:#1e1e1e;color:#e06c75;padding:2rem;font-family:monospace;">';
        echo '<b>' . get_class($e) . '</b>: ' . htmlspecialchars($e->getMessage()) . "\n\n";
        echo htmlspecialchars($e->getTraceAsString());
        echo '</pre>';
    } else {
        http_response_code(500);
        require_once '../app/views/500.php';
    }
});

// ── 8. Router ──────────────────────────────────────────────────────────────────
$router = new Router();
require_once '../routes/web.php';
require_once '../routes/api.php';
$router->dispatch($requestUri);
