<?php

namespace App\Repository\Interfaces;

interface CompteRepositoryInterface
{
    public function createSecondaryCompte(array $data): bool;
}