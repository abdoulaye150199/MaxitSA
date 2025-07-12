<?php

namespace App\Entite;

enum TypeCompte: string
{
    case PRINCIPALE = 'principale';
    case SECONDAIRE = 'secondaire';
}

enum TypeTransaction: string
{
    case PAIEMENT = 'PAIEMENT';
    case DEPOT = 'DEPOT';
    case RETRAIT = 'RETRAIT';
}