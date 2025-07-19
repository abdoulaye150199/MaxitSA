<?php

namespace App\Core;

use App\Core\Errors\{ValidationError, LoginError, AccountError};

class Validator
{
    private static array $errors = [];
    private static array $validationRules = [];
    private static array $validationMessages = [];

    private static function initializeRules(): void 
    {
        if (empty(self::$validationRules)) {
            self::$validationRules = [
                'telephone' => [
                    'pattern' => '/^(7[056789])[0-9]{7}$/',
                    'validator' => fn($value) => preg_match('/^(7[056789])[0-9]{7}$/', $value)
                ],
                'code_secret' => [
                    'pattern' => '/^[0-9]{4}$/',
                    'validator' => fn($value) => preg_match('/^[0-9]{4}$/', $value)
                ],
                'min_amount' => [
                    'min' => 500,
                    'validator' => fn($value) => is_numeric($value) && $value >= 0
                ]
            ];
        }
    }

    private static function validateField(string $field, $value, array $rules): void
    {
        foreach ($rules as $rule => $customMessage) {
            if (!isset(self::$validationRules[$rule])) {
                continue;
            }

            $validator = self::$validationRules[$rule]['validator'];
            
            if (!$validator($value)) {
                self::$errors[$field] = $customMessage ?: self::$validationMessages[$rule];
                break;
            }
        }
    }

    public static function validate(array $data, array $rules): array
    {
        self::$errors = [];
        self::initializeRules();

        foreach ($rules as $field => $fieldRules) {
            if (!isset($data[$field])) {
                if (isset($fieldRules['required'])) {
                    self::$errors[$field] = $fieldRules['required'] ?: self::$validationMessages['required'];
                }
                continue;
            }

            self::validateField($field, $data[$field], $fieldRules);
        }

        return self::$errors;
    }

    public static function validateLogin(array $data): array
    {
        $rules = [
            'code' => [
                'required' => LoginError::CODE_REQUIRED->value,
                'code_secret' => LoginError::CODE_INVALID->value
            ]
        ];

        return self::validate($data, $rules);
    }

    public static function validateSecondaryAccount(array $data): array
    {
        $errors = [];

        // Validation du numéro de téléphone
        if (empty($data['numero_telephone'])) {
            $errors['numero_telephone'] = AccountError::NUMERO_REQUIRED->value;
        } elseif (!preg_match('/^(7[056789])[0-9]{7}$/', $data['numero_telephone'])) {
            $errors['numero_telephone'] = AccountError::NUMERO_INVALID->value;
        }

        // Validation du code secret
        if (empty($data['code_secret'])) {
            $errors['code_secret'] = AccountError::CODE_REQUIRED->value;
        } elseif (!preg_match('/^[0-9]{4}$/', $data['code_secret'])) {
            $errors['code_secret'] = AccountError::CODE_INVALID->value;
        }

        return $errors;
    }

    public static function validateUserExists($user): bool
    {
        return !empty($user) && isset($user['id']);
    }

    public static function validateCodeSecret(string $code): array
    {
        $errors = [];
        if (empty($code)) {
            $errors['code_secret'] = AccountError::CODE_REQUIRED->value;
        } elseif (!preg_match('/^[0-9]{4}$/', $code)) {
            $errors['code_secret'] = AccountError::CODE_INVALID->value;
        }
        return $errors;
    }

    public static function getErrors(): array
    {
        return self::$errors;
    }

    public static function isValid(): bool
    {
        return empty(self::$errors);
    }

    public static function clearErrors(): void
    {
        self::$errors = [];
    }
}