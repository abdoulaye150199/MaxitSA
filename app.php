<?php

namespace App\Core;

use App\Core\Database;
use App\Core\Router;
use App\Core\Session;
use App\Core\Validator;
use App\Core\FileUpload;
use App\Service\SmsService;

class App
{
    private static array $dependencies = [
        "core" => [
            "router" => null,
            "database" => null,
            "session" => null,
            "validator" => null,
            "fileUpload" => null,
        ],
        "services" => [
            "smsService" => null,
        ],
        "repositories" => [],
    ];

    private static array $instances = [];

    /**
     * Récupérer une dépendance
     */
    public static function getDependencie(string $key)
    {
        // Vérifier si l'instance existe déjà (singleton)
        if (isset(self::$instances[$key])) {
            return self::$instances[$key];
        }

        // Chercher dans les différentes catégories
        foreach (self::$dependencies as $category => $deps) {
            if (isset($deps[$key])) {
                $instance = self::createInstance($key);
                self::$instances[$key] = $instance;
                return $instance;
            }
        }

        throw new \Exception("Dépendance '{$key}' non trouvée");
    }

    /**
     * Créer une instance de la dépendance
     */
    private static function createInstance(string $key)
    {
        switch ($key) {
            case 'router':
                return new Router();
            case 'database':
                return Database::getInstance();
            case 'session':
                return Session::getInstance();
            case 'validator':
                return new Validator();
            case 'fileUpload':
                return new FileUpload();
            case 'smsService':
                return new SmsService();
            default:
                throw new \Exception("Instance '{$key}' non définie");
        }
    }

    /**
     * Méthode magique pour appeler getDependencie
     */
    public static function __callStatic(string $name, array $arguments)
    {
        return self::getDependencie($name);
    }

    /**
     * Convertir un objet en tableau
     */
    public static function toArray($object): array
    {
        if (is_array($object)) {
            return $object;
        }

        if (is_object($object)) {
            return get_object_vars($object);
        }

        return [$object];
    }

    /**
     * Enregistrer une dépendance personnalisée
     */
    public static function bind(string $category, string $key, $instance): void
    {
        self::$dependencies[$category][$key] = $instance;
    }
}