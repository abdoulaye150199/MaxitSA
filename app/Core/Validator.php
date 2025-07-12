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

    private static function validateRule(string $rule, $value, string $field): bool
    {
        switch ($rule) {
            case 'required':
                return !empty($value);
            
            case 'telephone_valid':
                return preg_match('/^(77|78)[0-9]{7}$/', $value);
            
            case 'cni_valid':
                return preg_match('/^(1|2)[0-9]{12}$/', $value);
            
            case 'code_secret':
                return preg_match('/^[0-9]{4}$/', $value);
            
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