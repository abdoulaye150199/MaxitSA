<?php

require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/env.php';

use App\Core\App;
use App\Core\Middleware;

// Initialiser l'application
App::init();

// Bootstrap des middlewares
Middleware::bootstrap();