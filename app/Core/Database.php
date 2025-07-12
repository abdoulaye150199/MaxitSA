<?php

namespace App\Core;

use PDO;

class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '5432';
        $dbname = $_ENV['DB_NAME'] ?? '';
        $user = $_ENV['DB_USER'] ?? '';
        $pass = $_ENV['DB_PASS'] ?? '';
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }
}
