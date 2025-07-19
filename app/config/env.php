<?php

require_once __DIR__ . '/../../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

define('DB_DSN', $_ENV['DB_DSN'] ?? '');
define('DB_HOST', $_ENV['DB_HOST'] ?? '127.0.0.1'); // Changé de HOST à DB_HOST
define('DB_USER', $_ENV['DB_USER']);
define('DB_PASS', $_ENV['DB_PASSWORD']);
define('DB_NAME', $_ENV['DB_NAME'] ?? 'Maxitsa');
define('DB_PORT', $_ENV['DB_PORT'] ?? '5432');
define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8000');

