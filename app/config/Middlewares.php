<?php
namespace App\Core;

class Middleware
{
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
            }
        }
    }

    private static function auth()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /');
            exit;
        }
    }



    private static function isClient()
    {
        if ($_SESSION['user']['type'] !== 'CLIENT') {
            http_response_code(403);
            echo "Accès interdit : réservé aux clients.";
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