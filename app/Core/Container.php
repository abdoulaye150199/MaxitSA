<?php

namespace App\Core;

use ReflectionClass;
use ReflectionParameter;
use Exception;

class Container
{
    private static ?Container $instance = null;
    private array $bindings = [];
    private array $instances = [];

    private function __construct() {}

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function bind(string $abstract, $concrete = null): void
    {
        $this->bindings[$abstract] = $concrete ?? $abstract;
    }

    public function singleton(string $abstract, $concrete = null): void
    {
        $this->bind($abstract, $concrete);
        $this->instances[$abstract] = null;
    }

    public function resolve(string $abstract)
    {
        // Si c'est un singleton et qu'il existe déjà
        if (array_key_exists($abstract, $this->instances) && $this->instances[$abstract] !== null) {
            return $this->instances[$abstract];
        }

        $concrete = $this->bindings[$abstract] ?? $abstract;

        if ($concrete instanceof \Closure) {
            $instance = $concrete($this);
        } else {
            $instance = $this->build($concrete);
        }

        // Si c'est un singleton, le stocker
        if (array_key_exists($abstract, $this->instances)) {
            $this->instances[$abstract] = $instance;
        }

        return $instance;
    }

    private function build(string $concrete)
    {
        $reflection = new ReflectionClass($concrete);

        if (!$reflection->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }

        $constructor = $reflection->getConstructor();

        if ($constructor === null) {
            return new $concrete;
        }

        $parameters = $constructor->getParameters();
        $dependencies = $this->resolveDependencies($parameters);

        return $reflection->newInstanceArgs($dependencies);
    }

    private function resolveDependencies(array $parameters): array
    {
        $dependencies = [];

        foreach ($parameters as $parameter) {
            $type = $parameter->getType();
            
            if ($type === null) {
                if ($parameter->isDefaultValueAvailable()) {
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Cannot resolve parameter {$parameter->getName()}");
                }
            } else {
                $dependencies[] = $this->resolve($type->getName());
            }
        }

        return $dependencies;
    }
}