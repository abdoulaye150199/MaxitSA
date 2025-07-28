<?php

// Configuration directe des variables d'environnement
define('DB_DSN', "pgsql:host=aws-0-eu-west-3.pooler.supabase.com;port=5432;dbname=postgres");
define('DB_DRIVER', 'pgsql');
define('DB_HOST', 'aws-0-eu-west-3.pooler.supabase.com');
define('DB_PORT', '5432');
define('DB_NAME', 'postgres');
define('DB_USER', 'postgres.koplwfnyoqkslijoxmkq');
define('DB_PASS', 'laye1234');

// Configuration de l'application
define('APP_URL', 'http://localhost:8000');
define('APP_ENV', 'development');
define('APP_DEBUG', true);

// Configuration des uploads
define('UPLOAD_MAX_SIZE', 5242880);
define('ALLOWED_IMAGE_TYPES', 'jpeg,jpg,png,gif,svg');

// Supprimez la partie chargement de .env puisque nous définissons directement les variables
// require_once __DIR__ . '/../../vendor/autoload.php';
// use Dotenv\Dotenv;
// $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
// $dotenv->load();

