<?php

namespace App\Service\Interfaces;

use App\Entite\Utilisateur;

interface UserServiceInterface
{
    public function register(array $userData): array;
    public function login(string $code): array;
    public function createUser(array $userData): array; // Changed from bool to array
    public function findUserByCode(string $code): ?Utilisateur;
    public function findUserByPhone(string $phone): ?Utilisateur;
}