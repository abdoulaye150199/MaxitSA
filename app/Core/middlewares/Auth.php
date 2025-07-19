<?php

namespace App\Core\Middlewares;

use App\Core\App;

class Auth
{
    private const SERVICE_COMMERCIAL_CODE = '0000';

    public function __invoke(): bool
    {
        $session = App::getDependency('session');
        
        if (!$session->has('user')) {
            header('Location: /login');
            exit;
        }

        return true;
    }

    public static function isServiceClient(): bool
    {
        $session = App::getDependency('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'serviceClient') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès interdit : réservé au service client']);
            exit;
        }

        return true;
    }
}