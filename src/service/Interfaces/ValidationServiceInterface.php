<?php

namespace App\Service\Interfaces;

interface ValidationServiceInterface
{
    public function validateRegistration(array $data): array;
    public function validateLogin(array $data): array;
    public function validateCodeSecret(array $data): array;
}