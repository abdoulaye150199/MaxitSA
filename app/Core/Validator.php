<?php

namespace App\Core;

use App\Core\Errors\ErrorMessages;

class Validator
{
    private static array $errors = [];

    public static function validate(array $data, array $rules): array
    {
        self::$errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? '';
            
            foreach ($fieldRules as $rule => $message) {
                if (!self::validateRule($rule, $value, $field)) {
                    self::$errors[$field] = $message ?: ErrorMessages::get($rule, $field);
                    break;
                }
            }
        }

        return self::$errors;
    }

    public static function validateLogin(array $data): array
    {
        return self::validate($data, [
            'code' => [
                'required' => ErrorMessages::get('code_required'),
                'code_secret' => ErrorMessages::get('code_invalid')
            ]
        ]);
    }

    public static function validateSearchAccount(array $data): array
    {
        return self::validate($data, [
            'numero' => [
                'required' => ErrorMessages::get('numero_required'),
                'telephone_valid' => ErrorMessages::get('numero_invalid')
            ]
        ]);
    }

    public static function validateSecondaryAccount(array $data): array
    {
        return self::validate($data, [
            'numero_telephone' => [
                'required' => ErrorMessages::get('numero_required'),
                'telephone_valid' => ErrorMessages::get('numero_invalid')
            ],
            'code_secret' => [
                'required' => ErrorMessages::get('code_required'),
                'code_secret' => ErrorMessages::get('code_invalid')
            ],
            'montant_initial' => [
                'required' => ErrorMessages::get('montant_required'),
                'min_amount' => ErrorMessages::get('montant_invalid')
            ]
        ]);
    }

    private static function validateRule(string $rule, $value, string $field): bool
    {
        switch ($rule) {
            case 'required':
                return !empty($value);
            case 'telephone_valid':
                return preg_match('/^[0-9]{9}$/', $value);
            case 'code_secret':
                return preg_match('/^[0-9]{4}$/', $value);
            case 'min_amount':
                return is_numeric($value) && $value >= 500;
            default:
                return true;
        }
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