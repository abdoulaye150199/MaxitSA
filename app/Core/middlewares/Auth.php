<?php

namespace App\Core\Middlewares;

use App\Core\App;

class Auth
{
    public function __invoke(): bool
    {
        $session = App::getDependencie('session');
        
        if (!$session->has('user')) {
            header('Location: /login');
            exit;
        }

        return true;
    }

    /**
     * Vérifier si l'utilisateur est un client
     */
    public static function isClient(): bool
    {
        $session = App::getDependencie('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'CLIENT') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès interdit : réservé aux clients']);
            exit;
        }

        return true;
    }

    /**
     * Vérifier si l'utilisateur est un agent
     */
    public static function isAgent(): bool
    {
        $session = App::getDependencie('session');
        $user = $session->get('user');
        
        if (!$user || $user['type'] !== 'AGENT') {
            http_response_code(403);
            echo json_encode(['error' => 'Accès interdit : réservé aux agents']);
            exit;
        }

        return true;
    }
}