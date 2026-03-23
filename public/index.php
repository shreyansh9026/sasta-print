<?php
session_start();

require_once '../config/config.php';
require_once '../config/database.php';

// Simple autoloader
spl_autoload_register(function($className) {
    if (file_exists('../app/controllers/' . $className . '.php')) {
        require_once '../app/controllers/' . $className . '.php';
    } elseif (file_exists('../app/models/' . $className . '.php')) {
        require_once '../app/models/' . $className . '.php';
    } elseif (file_exists('../app/' . $className . '.php')) {
        require_once '../app/' . $className . '.php';
    }
});

$router = new Router();
require_once '../routes/web.php';
require_once '../routes/api.php';

$router->dispatch($_GET['url'] ?? '/');
