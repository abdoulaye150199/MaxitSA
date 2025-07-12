<?php

namespace App\Core;

use App\Providers\DatabaseServiceProvider;
use App\Providers\RepositoryServiceProvider;
use App\Providers\ServiceServiceProvider;
use App\Providers\SessionServiceProvider;

class App
{
    private static ?App $instance = null;
    private Container $container;
    private array $providers = [];

    private function __construct()
    {
        $this->container = Container::getInstance();
        $this->registerProviders();
        $this->bootProviders();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function session(): Session
    {
        return self::getInstance()->resolve(Session::class);
    }

    public function resolve(string $abstract)
    {
        return $this->container->resolve($abstract);
    }

    private function registerProviders(): void
    {
        $providers = [
            DatabaseServiceProvider::class,
            RepositoryServiceProvider::class,
            ServiceServiceProvider::class,
            SessionServiceProvider::class,
        ];

        foreach ($providers as $providerClass) {
            $provider = new $providerClass($this->container);
            $provider->register();
            $this->providers[] = $provider;
        }
    }

    private function bootProviders(): void
    {
        foreach ($this->providers as $provider) {
            $provider->boot();
        }
    }
}