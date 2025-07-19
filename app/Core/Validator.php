<?php

namespace App\Core;

class Validator 
{
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
        ],
        'montant' => [
            'min' => 500,
            'message' => 'Le montant doit être d\'au moins %d FCFA'
        ],
        'account_number' => [
            'pattern' => '/^[PS]-\d{4}$/',
            'message' => 'Format de numéro de compte invalide'
        ],
        'file' => [
            'max_size' => 5242880, // 5MB
            'allowed_types' => ['image/jpeg', 'image/png', 'image/gif'],
            'messages' => [
                'upload_error' => 'Erreur lors de l\'upload du fichier',
                'type_invalid' => 'Type de fichier non autorisé',
                'size_invalid' => 'Fichier trop volumineux'
            ]
        ]
    ];

    private static function validateRequiredField(string $field, $value): ?string 
    {
        if (empty($value)) {
            return sprintf(self::$validationRules['required']['message'], $field);
        }
        return null;
    }

    // Pour les appels statiques externes
    public static function validateRequired($value, string $field): ?string
    {
        return self::validateRequiredField($field, $value);
    }

    // Changer en méthode statique
    private static function validate(array $data, array $rules): array 
    {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule) {
                $value = $data[$field] ?? null;
                $error = match($rule) {
                    'required' => self::validateRequiredField($field, $value),
                    'telephone' => self::validatePattern('telephone', $value),
                    'code_secret' => self::validatePattern('code_secret', $value),
                    'account_number' => self::validatePattern('account_number', $value),
                    'montant_min' => self::validateMinAmount($value ?? 0, self::$validationRules['montant']['min']),
                    'file' => self::validateFile($value),
                    default => null
                };
                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }
        return $errors;
    }

    // Changer aussi ces méthodes en statiques
    private static function validatePattern(string $rule, $value): ?string 
    {
        if (!empty($value) && !preg_match(self::$validationRules[$rule]['pattern'], $value)) {
            return self::$validationRules[$rule]['message'];
        }
        return null;
    }

    private static function validateMinAmount(float $amount, float $min): ?string 
    {
        if ($amount < $min) {
            return sprintf(self::$validationRules['montant']['message'], $min);
        }
        return null;
    }

    private static function validateFile(array $file): ?string
    {
        if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
            return self::$validationRules['file']['messages']['upload_error'];
        }
        if (!in_array($file['type'], self::$validationRules['file']['allowed_types'])) {
            return self::$validationRules['file']['messages']['type_invalid'];
        }
        if ($file['size'] > self::$validationRules['file']['max_size']) {
            return self::$validationRules['file']['messages']['size_invalid'];
        }
        return null;
    }

    public static function validateRegistration(array $data): array
    {
        return self::validate($data, [
            'numero' => ['required', 'telephone'],
            'nom' => ['required'],
            'prenom' => ['required'],
            'adresse' => ['required'],
            'numero_carte_identite' => ['required']
        ]);
    }

    public static function validateLogin(array $data): array
    {
        return self::validate($data, [
            'code' => ['required', 'code_secret']
        ]);
    }

    public static function validateSecondaryAccount(array $data): array
    {
        return self::validate($data, [
            'numero_telephone' => ['required', 'telephone'],
            'code_secret' => ['required', 'code_secret'],
            'montant_initial' => ['required', 'montant_min']
        ]);
    }

    public static function validateUserSession($registerData): bool
    {
        return empty($registerData);
    }

    public static function validateCodeSecretRegistration(?string $codeSecret): array
    {
        $errors = [];
        if (empty($codeSecret)) {
            $errors['code_secret'] = 'Le code secret est obligatoire';
        } elseif (!preg_match(self::$validationRules['code_secret']['pattern'], $codeSecret)) {
            $errors['code_secret'] = self::$validationRules['code_secret']['message'];
        }
        return $errors;
    }

    public static function validateUserExists($user): bool
    {
        return !empty($user);
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
        
        // Validate page
        if (!filter_var($data['page'], FILTER_VALIDATE_INT) || $data['page'] < 1) {
            $errors['page'] = 'Numéro de page invalide';
        }

        // Validate date if present
        if (!empty($data['date'])) {
            $date = \DateTime::createFromFormat('d/m/Y', $data['date']);
            if (!$date) {
                $errors['date'] = 'Format de date invalide';
            }
        }

        return $errors;
    }
    
    public static function validateCompteCreation(array $data): array
    {
        return self::validate($data, [
            'numero_telephone' => ['required', 'telephone'],
            'code_secret' => ['required', 'code_secret'],
            'montant_initial' => ['montant_min']
        ]);
    }

    public static function validateAccountNumber(?string $numero): ?string
    {
        if (empty($numero)) {
            return 'Le numéro de compte est obligatoire';
        }
        if (!preg_match(self::$validationRules['account_number']['pattern'], $numero)) {
            return self::$validationRules['account_number']['message'];
        }
        return null;
    }

    public static function validatePhoneNumber(?string $numero): ?string
    {
        if (empty($numero)) {
            return 'Le numéro de téléphone est obligatoire';
        }
        if (!preg_match(self::$validationRules['telephone']['pattern'], $numero)) {
            return self::$validationRules['telephone']['message'];
        }
        return null;
    }
}