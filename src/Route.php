<?php

declare(strict_types=1);

namespace Duyler\Router;

use RuntimeException;

class Route
{
    private static RouteCollection $routeCollection;

    public function __construct(RouteCollection $routeCollection)
    {
        static::$routeCollection = $routeCollection;
    }

    public static function get(string $pattern): RouteDefinition
    {
        if (static::$routeCollection === null) {
            static::throwNotInitialized();
        }
        $route = new RouteDefinition('get', $pattern);
        static::$routeCollection->add($route);
        return $route;
    }

    public static function post(string $pattern): RouteDefinition
    {
        if (static::$routeCollection === null) {
            static::throwNotInitialized();
        }
        $route = new RouteDefinition('post', $pattern);
        static::$routeCollection->add($route);
        return $route;
    }

    public static function put(string $pattern): RouteDefinition
    {
        if (static::$routeCollection === null) {
            static::throwNotInitialized();
        }
        $route = new RouteDefinition('put', $pattern);
        static::$routeCollection->add($route);
        return $route;
    }

    public static function patch(string $pattern): RouteDefinition
    {
        if (static::$routeCollection === null) {
            static::throwNotInitialized();
        }
        $route = new RouteDefinition('patch', $pattern);
        static::$routeCollection->add($route);
        return $route;
    }

    public static function delete(string $pattern): RouteDefinition
    {
        if (static::$routeCollection === null) {
            static::throwNotInitialized();
        }
        $route = new RouteDefinition('delete', $pattern);
        static::$routeCollection->add($route);
        return $route;
    }

    private static function throwNotInitialized(): never
    {
        throw new RuntimeException('Router is not initialized');
    }
}
