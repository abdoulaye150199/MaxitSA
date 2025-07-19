<?php


namespace App\Core\Middlewares;

use App\Core\App;

class IsServiceClient
{
    public function __invoke(): bool
    {
        $session = App::getDependency('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'serviceClient') {
            http_response_code(403);
            echo "Accès interdit : réservé au service client.";
            return false;
        }
        
        return true;
    }
}