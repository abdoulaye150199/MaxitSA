<?php

namespace App\Core;

use PDO;

class Database
{
    private static $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            $host = DB_HOST ?? '127.0.0.1';
            $port = DB_PORT ?? '5432';
            $dbname =DB_NAME ?? '';
            $user = DB_USER ?? '';
            $pass = DB_PASS ?? '';
            $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
            error_log("HOST: $host, PORT: $port, DB: $dbname, USER: $user, PASS: $pass");
            self::$instance = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
            // self::$instance = new PDO($dsn, $user, $pass);
            // self::$instance-> setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);
            // self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


        }
        return self::$instance;
    }
}
