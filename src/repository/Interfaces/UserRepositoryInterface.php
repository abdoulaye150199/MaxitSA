<?php

namespace App\Repository\Interfaces;

use App\Entite\Utilisateur;

interface UserRepositoryInterface
{
    public function findAll(): array;
    public function findById(int $id): ?Utilisateur;
    public function findByCode(string $code): ?Utilisateur;
    public function findByPhone(string $phone): ?Utilisateur;
    public function create(Utilisateur $user): bool;
    public function update(Utilisateur $user): bool;
}