<?php

namespace App\Core\Middlewares;

use App\Core\App;

class IsClient
{
    public function __invoke(): bool
    {
        $session = App::getDependency('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'CLIENT') {
            http_response_code(403);
            echo "Accès interdit : réservé aux clients.";
            return false;
        }
        
        return true;
    }
}