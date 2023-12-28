<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Exception\RouteIsNotFoundForNameException;
use Duyler\Router\Exception\RouterIsNotInitializedException;
use Duyler\Router\Handler\UrlGenerator;

class Url
{
    protected static UrlGenerator $uriGenerator;

    public function __construct(UrlGenerator $uriGenerator)
    {
        static::$uriGenerator = $uriGenerator;
    }

    /**
     * @throws RouterIsNotInitializedException
     * @throws RouteIsNotFoundForNameException
     */
    public static function get(string $routeName, array $params = [], string $lang = ''): string
    {
        if (is_null(static::$uriGenerator)) {
            throw new RouterIsNotInitializedException();
        }

        new Route(static::$uriGenerator);

        return static::$uriGenerator->getUrl($routeName, $params, $lang);
    }
}
