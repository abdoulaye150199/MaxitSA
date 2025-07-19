<?php

namespace App\Core\Errors;

enum ValidationError: string
{
    case REQUIRED = 'Le champ %s est obligatoire.';
    case TELEPHONE_INVALID = 'Le numéro de téléphone est invalide.';
    case CNI_INVALID = 'Le numéro d\'identité n\'est pas valide.';
    case CODE_SECRET_INVALID = 'Le code secret doit contenir exactement 4 chiffres.';
    case MIN_AMOUNT = 'Le montant initial doit être d\'au moins 500 FCFA.';
}

enum LoginError: string
{
    case CODE_REQUIRED = 'Le code secret est obligatoire.';
    case CODE_INVALID = 'Code secret invalide.';
}

enum AccountError: string
{
    case NUMERO_REQUIRED = 'Le numéro de téléphone est obligatoire.';
    case NUMERO_INVALID = 'Format de numéro invalide.';
    case CODE_REQUIRED = 'Le code secret est obligatoire.';
    case CODE_INVALID = 'Le code secret doit contenir 4 chiffres.';
    case MONTANT_REQUIRED = 'Le montant initial est obligatoire.';
    case MONTANT_INVALID = 'Le montant initial doit être d\'au moins 500 FCFA.';
}

enum ErrorMessages: string
{
    case FILE_UPLOAD_ERROR = 'Erreur lors de l\'upload du fichier.';
    case FILE_TYPE_INVALID = 'Type de fichier non autorisé.';
    case FILE_SIZE_INVALID = 'Fichier trop volumineux.';

    // Méthode statique pour récupérer le message
    public static function get(string $key): string
    {
        return match ($key) {
            'file_upload_error' => self::FILE_UPLOAD_ERROR->value,
            'file_type_invalid' => self::FILE_TYPE_INVALID->value,
            'file_size_invalid' => self::FILE_SIZE_INVALID->value,
            default => 'Une erreur est survenue'
        };
    }
}