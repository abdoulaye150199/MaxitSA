<?php

namespace App\Providers;

use App\Core\ServiceProvider;
use App\Service\UserService;
use App\Service\SmsService;
use App\Service\ValidationService;
use App\Service\FileUploadService;
use App\Service\Interfaces\UserServiceInterface;
use App\Service\Interfaces\SmsServiceInterface;
use App\Service\Interfaces\ValidationServiceInterface;
use App\Service\Interfaces\FileUploadServiceInterface;

class ServiceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->container->bind(UserServiceInterface::class, UserService::class);
        $this->container->bind(SmsServiceInterface::class, SmsService::class);
        $this->container->bind(ValidationServiceInterface::class, ValidationService::class);
        $this->container->bind(FileUploadServiceInterface::class, FileUploadService::class);
    }
}