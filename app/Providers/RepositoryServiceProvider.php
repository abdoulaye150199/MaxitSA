<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Repository\UserRepository;
use App\Repository\Interfaces\UserRepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(UserRepositoryInterface::class, UserRepository::class);
    }
}