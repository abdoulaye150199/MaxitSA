<?php

namespace App\Core;

use Symfony\Component\Yaml\Yaml;

class App
{
    private static array $dependencies = [];
    private const CONFIG_PATH = '/config/services.yml';
    private const SERVICE_KEY = 'services';

    public static function init(): void
    {
        $services = self::loadServices();
        self::registerServices($services);
    }

    private static function loadServices(): array
    {
        $path = dirname(__DIR__) . self::CONFIG_PATH;
        return Yaml::parseFile($path)[self::SERVICE_KEY];
    }

    private static function registerServices(array $services): void
    {
        foreach ($services as $id => $config) {
            if (!isset(self::$dependencies[$id])) {
                self::$dependencies[$id] = self::createService($config);
            }
        }
    }

    private static function createService(array $config): object
    {
        $class = $config['class'];
        return isset($config['factory']) 
            ? $class::{$config['factory']}()
            : self::createInstance($class, $config['arguments'] ?? []);
    }

    private static function createInstance(string $class, array $arguments): object
    {
        return new $class(...self::resolveArguments($arguments));
    }

    private static function resolveArguments(array $arguments): array
    {
        return array_map(function ($arg) {
            if (!is_string($arg) || !str_starts_with($arg, '@')) {
                return $arg;
            }
            return self::resolveDependencyReference(substr($arg, 1));
        }, $arguments);
    }

    private static function resolveDependencyReference(string $reference): mixed
    {
        if (str_contains($reference, '.')) {
            [$service, $method] = explode('.', $reference);
            $instance = self::getDependency($service);
            return $instance->$method();
        }
        return self::getDependency($reference);
    }

    public static function getDependency(string $name): mixed
    {
        if (!array_key_exists($name, self::$dependencies)) {
            throw new \Exception("Dépendance non trouvée: $name");
        }
        return self::$dependencies[$name];
    }
}

