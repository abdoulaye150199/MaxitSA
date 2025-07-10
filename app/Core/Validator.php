<?php

namespace App\Core;

class Validator
{
    private static array $errors = [];

    private static array $rules = [];

    public function __construct()
    {
        self::$rules = [
            "required" => function ($value, $field, $message = null) {
                if (empty($value)) {
                    Validator::addError($field, $message ?? "Le champ $field est obligatoire.");
                }
            },

            "regex" => function ($value, $field, $pattern, $message = null) {
                if (!preg_match($pattern, $value)) {
                    Validator::addError($field, $message ?? "Le champ $field n'est pas valide.");
                }
            },

            "telephoneValide" => function ($value, $field, $message = null) {
                if (!preg_match('/^(77|78)[0-9]{7}$/', $value)) {
                    Validator::addError($field, $message ?? "$value n'est pas un numéro valide.");
                }
            },

            "CNIValide" => function ($value, $field, $message = null) {
                if (!preg_match('/^(1|2)[0-9]{12}$/', $value)) {
                    Validator::addError($field, $message ?? "$value n'est pas un CNI valide.");
                }
            }
        ];
    }

    public static function validate(string $critere, $value, string $field, $message = null, $extra = null)
    {
        if (isset(self::$rules[$critere])) {
            if ($critere === "regex" && $extra !== null) {
                self::$rules[$critere]($value, $field, $extra, $message);
            } else {
                self::$rules[$critere]($value, $field, $message);
            }
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

    private static function addError(string $field, string $message)
    {
        self::$errors[$field] = $message;
    }
}
