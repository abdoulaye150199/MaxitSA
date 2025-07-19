<?php

namespace App\Core;

use App\Core\Errors\{ValidationError, LoginError, AccountError};

class Validator
{
    private static array $errors = [];

    // Règles de validation existantes
    private static array $validationRules = [
        'telephone' => [
            'pattern' => '/^(7[056789])[0-9]{7}$/',
            'message' => 'Format de numéro invalide'
        ],
        'code_secret' => [
            'pattern' => '/^[0-9]{4}$/',
            'message' => 'Le code secret doit contenir exactement 4 chiffres'
        ],
        'required' => [
            'message' => 'Le champ %s est obligatoire'
        ]
    ];

    // Nouvelle méthode pour valider la création de compte
    public static function validateCompteCreation(array $data): array
    {
        $errors = [];

        // Validation du numéro de téléphone
        if (empty($data['numero_telephone'])) {
            $errors['numero_telephone'] = 'Le numéro de téléphone est obligatoire';
        } elseif (!preg_match(self::$validationRules['telephone']['pattern'], $data['numero_telephone'])) {
            $errors['numero_telephone'] = self::$validationRules['telephone']['message'];
        }

        // Validation du code secret
        if (empty($data['code_secret'])) {
            $errors['code_secret'] = 'Le code secret est obligatoire';
        } elseif (!preg_match(self::$validationRules['code_secret']['pattern'], $data['code_secret'])) {
            $errors['code_secret'] = self::$validationRules['code_secret']['message'];
        }

        return $errors;
    }

    // Nouvelle méthode pour valider le login
    public static function validateLogin(array $data): array
    {
        $errors = [];
        
        if (empty($data['code'])) {
            $errors['code'] = 'Le code est requis';
        } elseif (!preg_match(self::$validationRules['code_secret']['pattern'], $data['code'])) {
            $errors['code'] = self::$validationRules['code_secret']['message'];
        }

        return $errors;
    }

    // Nouvelle méthode pour valider l'enregistrement utilisateur
    public static function validateUserRegistration(array $data): array
    {
        $errors = [];
        
        $requiredFields = ['nom', 'prenom', 'numero', 'adresse'];
        foreach ($requiredFields as $field) {
            if (empty($data[$field])) {
                $errors[$field] = sprintf(self::$validationRules['required']['message'], $field);
            }
        }

        if (!empty($data['numero']) && !preg_match(self::$validationRules['telephone']['pattern'], $data['numero'])) {
            $errors['numero'] = self::$validationRules['telephone']['message'];
        }

        return $errors;
    }

    // Validation du code secret
    public static function validateCodeSecret(string $code): array
    {
        $errors = [];
        
        if (empty($code)) {
            $errors['code_secret'] = 'Le code secret est obligatoire';
        } elseif (!preg_match(self::$validationRules['code_secret']['pattern'], $code)) {
            $errors['code_secret'] = self::$validationRules['code_secret']['message'];
        }

        return $errors;
    }

    public static function validatePhoneNumber(?string $numero): ?string
    {
        if (empty($numero)) {
            return 'Le numéro est obligatoire';
        }
        $numero = str_replace(['+221', ' '], '', $numero);
        if (!preg_match('/^(7[056789])[0-9]{7}$/', $numero)) {
            return 'Format de numéro invalide';
        }
        return null;
    }

    public static function validateAccountNumber(?string $numeroCompte): ?string
    {
        if (empty($numeroCompte)) {
            return 'Le numéro de compte est obligatoire';
        }
        if (!preg_match('/^[PS]-\d{4}$/', $numeroCompte)) {
            return 'Format de numéro de compte invalide';
        }
        return null;
    }

    public static function validateRequired($value, $field): ?string
    {
        if (empty($value)) {
            return "Le champ $field est obligatoire";
        }
        return null;
    }

    public static function validateLoginCode(?string $code): ?string 
    {
        if (empty($code)) {
            return 'Le code est requis';
        }
        if (!preg_match(self::$validationRules['code_secret']['pattern'], $code)) {
            return 'Format de code invalide';
        }
        return null;
    }

    public static function validateTransactionFilters(array $data): array 
    {
        $errors = [];
        
        // Validation de la page
        $page = $data['page'] ?? 1;
        if (!filter_var($page, FILTER_VALIDATE_INT) || $page < 1) {
            $errors['page'] = 'Numéro de page invalide';
        }

        // Validation de la date
        if (!empty($data['date'])) {
            $date = \DateTime::createFromFormat('d/m/Y', $data['date']);
            if (!$date) {
                $errors['date'] = 'Format de date invalide';
            }
        }

        return $errors;
    }

    public static function validateUserSession($user): ?string 
    {
        if (!$user) {
            return 'Session utilisateur invalide';
        }
        return null;
    }

    public static function validateCodeSecretRegistration(string $code): array 
    {
        $errors = [];
        
        if (empty($code)) {
            $errors['code_secret'] = 'Le code secret est requis';
        } elseif (!preg_match(self::$validationRules['code_secret']['pattern'], $code)) {
            $errors['code_secret'] = 'Le code doit contenir exactement 4 chiffres';
        }

        return $errors;
    }

    public static function validateUserExists($user): bool
    {
        return !empty($user);
    }
}