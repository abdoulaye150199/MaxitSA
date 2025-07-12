<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Repository\UserRepository;
use App\Repository\Interfaces\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(UserRepositoryInterface::class, function($container) {
            $database = $container->resolve(\App\Core\Database::class);
            return new UserRepository($database);
        });
    }
}