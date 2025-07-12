<?php

// Charger les helpers
require_once __DIR__ . '/helpers.php';

// Initialiser les middlewares
use App\Core\Middleware;

Middleware::bootstrap();

// Définir les constantes de configuration
if (!defined('DB_HOST')) {
    define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1');
    define('DB_PORT', $_ENV['DB_PORT'] ?? '5432');
    define('DB_NAME', $_ENV['DB_NAME'] ?? 'maxitsa');
    define('DB_USER', $_ENV['DB_USER'] ?? 'postgres');
    define('DB_PASS', $_ENV['DB_PASS'] ?? 'password');
    define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000');
    define('APP_ENV', $_ENV['APP_ENV'] ?? 'development');
    define('APP_DEBUG', $_ENV['APP_DEBUG'] ?? 'true');
}