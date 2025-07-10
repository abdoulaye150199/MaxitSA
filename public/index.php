<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$start = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__ . '/../app/config/bootstrap.php';
require_once __DIR__ . '/../routes/route.web.php';

use App\Core\Router;
Router::resolve();

error_log('Temps d\'exécution : ' . (microtime(true) - $start) . ' secondes');

