<?php

namespace App\Core\Errors;

class ErrorMessages
{
    public const VALIDATION_ERRORS = [
        'required' => 'Le champ %s est obligatoire.',
        'telephone_invalid' => '%s n\'est pas un numéro valide.',
        'cni_invalid' => '%s n\'est pas un CNI valide.',
        'code_secret_invalid' => 'Le code secret doit contenir exactement 4 chiffres.',
        'file_upload_error' => 'Erreur lors de l\'upload du fichier.',
        'file_type_invalid' => 'Type de fichier non autorisé.',
        'file_size_invalid' => 'Fichier trop volumineux.',
    ];

    public const USER_ERRORS = [
        'user_not_found' => 'Utilisateur non trouvé.',
        'invalid_credentials' => 'Code secret incorrect.',
        'registration_failed' => 'Erreur lors de l\'inscription. Veuillez réessayer.',
        'user_already_exists' => 'Un utilisateur avec ce numéro existe déjà.',
    ];

    public const SMS_ERRORS = [
        'sms_send_failed' => 'Erreur lors de l\'envoi du SMS.',
        'sms_config_missing' => 'Configuration SMS manquante.',
    ];

    public static function get(string $key, ...$params): string
    {
        $message = self::VALIDATION_ERRORS[$key] 
                ?? self::USER_ERRORS[$key] 
                ?? self::SMS_ERRORS[$key] 
                ?? 'Erreur inconnue.';

        return sprintf($message, ...$params);
    }
}