<?php
namespace App\Core;

class App {
    private static array $dependencies = [
        'core' => [
            'router' => null,
            'database' => null,
        ],
        'services' => [],
        'repositories' => [],
    ];

    public static function getDependencie(string $key) {
        if (isset(self::$dependencies['core'][$key])) {
            if (self::$dependencies['core'][$key] === null) {
                switch ($key) {
                    case 'router':
                        self::$dependencies['core'][$key] = new Router();
                        break;
                    case 'database':
                        self::$dependencies['core'][$key] = Database::getInstance();
                        break;
                }
            }
            return self::$dependencies['core'][$key];
        }

        return null;
    }
}