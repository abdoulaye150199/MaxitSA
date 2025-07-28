<?php

namespace App\Repository\Interfaces;

use App\Entite\Compte;

interface CompteRepositoryInterface
{
    public function createSecondaryCompte(array $data): bool;
    public function findByNumero(string $numero): ?Compte;
    public function findByNumeroCompte(string $numeroCompte): ?Compte;
    public function findByUserId(int $userId): ?Compte;
    public function findAllByUserId(int $userId): array;
    public function getLastAccountNumber(string $prefix): int;
}