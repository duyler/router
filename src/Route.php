<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Contract\RouteHandlerInterface;
use Exception;

class Route
{
    protected static null|RouteHandlerInterface $handler = null;

    protected static string $routesDirPath = '';

    public function __construct(RouteHandlerInterface $handler)
    {
        static::$handler = $handler;
    }

    public static function get(string $pattern): RouteHandlerInterface
    {
        if (is_null(static::$handler)) {
            throw new Exception('Router is not initialized');
        }

        return static::$handler->route('get', $pattern);
    }

    public static function post(string $pattern): RouteHandlerInterface
    {
        if (is_null(static::$handler)) {
            throw new Exception('Router is not initialized');
        }

        return static::$handler->route('post', $pattern);
    }

    public static function put(string $pattern): RouteHandlerInterface
    {
        if (is_null(static::$handler)) {
            throw new Exception('Router is not initialized');
        }

        return static::$handler->route('put', $pattern);
    }

    public static function patch(string $pattern): RouteHandlerInterface
    {
        if (is_null(static::$handler)) {
            throw new Exception('Router is not initialized');
        }

        return static::$handler->route('patch', $pattern);
    }

    public static function delete(string $pattern): RouteHandlerInterface
    {
        if (is_null(static::$handler)) {
            throw new Exception('Router is not initialized');
        }

        return static::$handler->route('delete', $pattern);
    }
}
