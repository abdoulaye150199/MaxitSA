<?php

if (!function_exists('dd')) {
    function dd(...$vars) {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die();
    }
}

if (!function_exists('env')) {
    function env(string $key, $default = null) {
        return $_ENV[$key] ?? $default;
    }
}

if (!function_exists('config')) {
    function config(string $key, $default = null) {
        return $default;
    }
}

if (!function_exists('redirect')) {
    function redirect(string $url) {
        header("Location: {$url}");
        exit;
    }
}

if (!function_exists('old')) {
    function old(string $key, $default = '') {
        return $_SESSION['old'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    function csrf_token(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('asset')) {
    function asset(string $path): string {
        return '/' . ltrim($path, '/');
    }
}