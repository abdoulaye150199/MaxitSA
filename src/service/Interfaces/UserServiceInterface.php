<?php

namespace App\Service\Interfaces;

use App\Entite\Utilisateur;

interface UserServiceInterface
{
    public function register(array $userData): array;
    public function login(string $code): array;
    public function createUser(array $userData): bool;
    public function findUserByCode(string $code): ?Utilisateur;
    public function findUserByPhone(string $phone): ?Utilisateur;
}