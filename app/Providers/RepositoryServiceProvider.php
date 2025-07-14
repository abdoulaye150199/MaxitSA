<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Repository\UserRepository;
use App\Repository\Interfaces\UserRepositoryInterface;
use App\Repository\TransactionRepository;
use App\Controller\TransactionController;
use App\Core\Database;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(UserRepositoryInterface::class, function($container) {
            $database = $container->resolve(\App\Core\Database::class);
            return new UserRepository($database);
        });

        $this->container->bind(TransactionController::class, function($container) {
            $database = $container->resolve(Database::class);
            return new TransactionController($database);
        });
    }
}