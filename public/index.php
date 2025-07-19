<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$start = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/config/bootstrap.php';

\App\Core\App::getDependency('router')->resolve();

error_log('Temps d\'exécution : ' . (microtime(true) - $start) . ' secondes');