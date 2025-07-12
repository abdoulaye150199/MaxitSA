<?php

namespace App\Core\Middlewares;

class CryptPassword
{
    public function __invoke(): bool
    {
        // Crypter le code secret si présent
        if (isset($_POST['code_secret'])) {
            $_POST['code_secret'] = password_hash($_POST['code_secret'], PASSWORD_DEFAULT);
        }

        // Crypter le mot de passe si présent
        if (isset($_POST['password'])) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        return true;
    }

    /**
     * Vérifier un mot de passe
     */
    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Générer un hash pour un mot de passe
     */
    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}