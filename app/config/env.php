<?php

// function loadEnv($path)
// {
//     if (!file_exists($path)) return;
//     $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//     foreach ($lines as $line) {
//         if (str_starts_with(trim($line), '#')) continue;
//         if (strpos($line, '=') !== false) {
//             list($name, $value) = array_map('trim', explode('=', $line, 2));
//         }
//         $_ENV[$name] = $value;
//         putenv("$name=$value");
//     }
// }
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__,2));
$dotenv->load();


define('DB_HOST', getenv('DB_HOST'));
define('DB_PORT', getenv('DB_PORT'));
define('DB_NAME', getenv('DB_NAME'));
define('DB_USER', getenv('DB_USER'));
define('DB_PASS', getenv('DB_PASS'));
define('APP_URL', getenv('APP_URL'));

