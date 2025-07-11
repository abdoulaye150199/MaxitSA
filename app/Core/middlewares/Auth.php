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
}