<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables from the project root directory
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
try {
    $dotenv->load();
} catch (\Dotenv\Exception\InvalidPathException $e) {
    die('Error: .env file not found. Please create one from .env.example');
}

// Initialize the application
App\Core\App::init();