<?php

namespace App\Core\Errors;

class ErrorMessages
{
    public const VALIDATION_ERRORS = [
        'required' => 'Le champ %s est obligatoire.',
        'telephone_valid' => 'Le numéro de téléphone est invalide.',
        'cni_invalid' => 'Le numéro d\'identité n\'est pas valide.',
        'code_secret' => 'Le code secret doit contenir exactement 4 chiffres.',
        'min_amount' => 'Le montant initial doit être d\'au moins 500 FCFA.',
        'file_upload_error' => 'Erreur lors de l\'upload du fichier.',
        'file_type_invalid' => 'Type de fichier non autorisé.',
        'file_size_invalid' => 'Fichier trop volumineux.'
    ];

    public const LOGIN_ERRORS = [
        'code_required' => 'Le code secret est obligatoire.',
        'code_invalid' => 'Le code secret doit contenir exactement 4 chiffres.'
    ];

    public const ACCOUNT_ERRORS = [
        'numero_required' => 'Le numéro de téléphone est obligatoire.',
        'numero_invalid' => 'Format de numéro invalide.',
        'code_required' => 'Le code secret est obligatoire.',
        'code_invalid' => 'Le code secret doit contenir 4 chiffres.',
        'montant_required' => 'Le montant initial est obligatoire.',
        'montant_invalid' => 'Le montant initial doit être d\'au moins 500 FCFA.'
    ];

    public const USER_ERRORS = [
        'user_not_found' => 'Utilisateur non trouvé.',
        'invalid_credentials' => 'Code secret incorrect.',
        'registration_failed' => 'Erreur lors de l\'inscription. Veuillez réessayer.',
        'user_already_exists' => 'Un utilisateur avec ce numéro existe déjà.'
    ];

    public const SMS_ERRORS = [
        'sms_send_failed' => 'Erreur lors de l\'envoi du SMS.',
        'sms_config_missing' => 'Configuration SMS manquante.'
    ];

    public static function get(string $key, ...$params): string
    {
        $message = self::VALIDATION_ERRORS[$key] 
                ?? self::LOGIN_ERRORS[$key]
                ?? self::ACCOUNT_ERRORS[$key]
                ?? self::USER_ERRORS[$key] 
                ?? self::SMS_ERRORS[$key] 
                ?? 'Erreur inconnue.';

        return sprintf($message, ...$params);
    }
}