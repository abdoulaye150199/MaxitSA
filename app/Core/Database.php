<?php

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?self $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        // Fix: Use correct absolute path to env.php
        require_once __DIR__ . '/../config/env.php';
        
        try {
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false
            ];

            if (!defined('DB_DSN') || !defined('DB_USER') || !defined('DB_PASS')) {
                throw new \RuntimeException(
                    'Configuration de base de données manquante. Vérifiez votre fichier .env'
                );
            }

            $dsn = DB_DSN . ";sslmode=require";
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            
            $this->pdo->query('SELECT 1');
            
        } catch (PDOException $e) {
            throw new \RuntimeException(
                'Erreur de connexion à Supabase: ' . $e->getMessage()
            );
        }
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
