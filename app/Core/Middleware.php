<?php

namespace App\Core;

use App\Core\App;

class Middleware
{
    private static array $middlewares = [];

    public static function register(string $name, callable $middleware): void
    {
        self::$middlewares[$name] = $middleware;
    }

    public static function handle(array $middlewares): void
    {
        foreach ($middlewares as $middleware) {
            if (isset(self::$middlewares[$middleware])) {
                $result = self::$middlewares[$middleware]();
                if ($result === false) {
                    break;
                }
            } else {
                // Fallback vers l'ancienne méthode
                self::handleLegacy($middleware);
            }
        }
    }

    private static function handleLegacy(string $middleware): void
    {
        switch ($middleware) {
            case 'login':
                self::auth();
                break;
            case 'isClient':
                self::isClient();
                break;
            case 'hashCodeSecret':
                self::hashCodeSecret();
                break;
        }
    }

    private static function auth(): bool
    {
        $session = App::session();
        if (!$session->has('user')) { // Changed from isset() to has()
            header('Location: /');
            exit;
        }
        return true;
    }
    private static function isClient(): bool
    {
        $session = App::session();
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'CLIENT') {
            http_response_code(403);
            echo "Accès interdit : réservé aux clients.";
            exit;
        }
        return true;
    }

    public static function hashCodeSecret(): bool
    {
        if (isset($_POST['code']) && !empty($_POST['code'])) {
            $_POST['code'] = password_hash($_POST['code'], PASSWORD_DEFAULT);
        }
        return true;
    }

    public static function bootstrap(): void
    {
        self::register('auth', function() {
            return self::auth();
        });

        self::register('isClient', function() {
            return self::isClient();
        });

        self::register('hashCodeSecret', function() {
            return self::hashCodeSecret();
        });

        self::register('cors', function() {
            header('Access-Control-Allow-Origin: *');
            header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
            header('Access-Control-Allow-Headers: Content-Type, Authorization');
            return true;
        });
    }
}