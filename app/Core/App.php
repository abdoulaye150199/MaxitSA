<?php

namespace App\Core;

use App\Core\Database;
use App\Core\Session;
use App\Repository\UserRepository;
use App\Repository\TransactionRepository;
use App\Service\UserService;
use App\Service\SmsService;
use App\Service\ValidationService;
use App\Service\FileUploadService;
use App\Service\Interfaces\UserServiceInterface;
use App\Service\Interfaces\SmsServiceInterface;
use App\Service\Interfaces\ValidationServiceInterface;
use App\Service\Interfaces\FileUploadServiceInterface;
use App\Repository\Interfaces\UserRepositoryInterface;

class App
{
    private static ?App $instance = null;
    private Container $container;
    private array $dependencies = [];

    private function __construct()
    {
        $this->container = Container::getInstance();
        $this->registerServices();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function registerServices(): void 
    {
        // Database Service
        $this->container->singleton(Database::class, function () {
            return Database::getInstance();
        });

        // Session Service
        $this->container->singleton(Session::class, function () {
            return Session::getInstance();
        });

        // Repository Services
        $this->container->bind(UserRepositoryInterface::class, function($container) {
            $database = $container->resolve(Database::class);
            return new UserRepository($database);
        });

        // Application Services
        $this->container->bind(UserServiceInterface::class, UserService::class);
        $this->container->bind(SmsServiceInterface::class, SmsService::class);
        $this->container->bind(ValidationServiceInterface::class, ValidationService::class);
        $this->container->bind(FileUploadServiceInterface::class, FileUploadService::class);
    }

    public static function session(): Session
    {
        return self::getInstance()->resolve(Session::class);
    }

    public static function validator(): Validator
    {
        return new Validator();
    }

    public function resolve(string $abstract)
    {
        return $this->container->resolve($abstract);
    }

    public function getDependency(string $name)
    {
        return $this->dependencies[$name] ?? null;
    }

    public function setDependency(string $name, $dependency): void
    {
        $this->dependencies[$name] = $dependency;
    }
}