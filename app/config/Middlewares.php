<?php

namespace App\Core;

class Middleware
{
    public static function bootstrap()
    {
        session_start();
    }

    public static function handle(array $middlewares)
    {
        foreach ($middlewares as $middleware) {
            switch ($middleware) {
                case 'login':
                    self::auth();
                    break;
                case 'isClient':
                    self::isClient();
                    break;
                case 'isAgent':
                    self::isAgent();
                    break;
                case 'isServiceClient':
                    self::isServiceClient();
                    break;
            }
        }
    }

    private static function auth()
    {
        $session = App::getDependency('session');
        
        if (!$session->has('user')) {
            header('Location: /login');
            exit;
        }
    }

    private static function isClient()
    {
       $session = App::getDependency('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'CLIENT') {
            http_response_code(403);
            echo "Accès interdit : réservé aux clients.";
            exit;
        }
    }

    private static function isAgent()
    {
        $session = App::getDependency('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'AGENT') {
            http_response_code(403);
            echo "Accès interdit : réservé aux agents.";
            exit;
        }
    }

    private static function isServiceClient()
    {
       $session = App::getDependency('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'serviceClient') {
            http_response_code(403);
            echo "Accès interdit : réservé au service client.";
            exit;
        }
    }

    public static function hashCodeSecret()
    {
        if (isset($_POST['code_secret'])) {
            $_POST['code_secret'] = password_hash($_POST['code_secret'], PASSWORD_DEFAULT);
        }
    }
}