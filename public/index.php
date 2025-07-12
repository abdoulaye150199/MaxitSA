<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$start = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Charger la configuration
require_once __DIR__ . '/../app/config/bootstrap.php';

// Charger les routes
require_once __DIR__ . '/../routes/route.web.php';

// Initialiser l'application
use App\Core\App;
use App\Core\Router;

try {
    $app = App::getInstance();
    $router = $app->resolve(Router::class);
    $router->resolve();
} catch (Exception $e) {
    error_log('Application Error: ' . $e->getMessage());
    http_response_code(500);
    echo "Une erreur est survenue. Veuillez réessayer plus tard.";
}

error_log('Temps d\'exécution : ' . (microtime(true) - $start) . ' secondes');