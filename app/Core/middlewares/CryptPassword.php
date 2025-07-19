<?php

namespace App\Core\Middlewares;

class CryptPassword
{
    public function __invoke(): bool
    {
        if (isset($_POST['code_secret'])) {
            $_POST['code_secret'] = password_hash($_POST['code_secret'], PASSWORD_DEFAULT);
        }

        if (isset($_POST['password'])) {
            $_POST['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }

        return true;
    }

    public static function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function hash(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }
}