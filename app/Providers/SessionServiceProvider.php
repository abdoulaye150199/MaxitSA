<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Core\Session;

class SessionServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Session::class, function () {
            return Session::getInstance();
        });
    }
}