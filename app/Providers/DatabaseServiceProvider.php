<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Core\Database;

class DatabaseServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->singleton(Database::class, function () {
            return Database::getInstance();
        });
    }
}