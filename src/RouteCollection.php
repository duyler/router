<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Handler\UrlGenerator;

class RouteCollection
{
    /** @var RouteDefinition[]  */
    private array $routes = [];

    public function __construct()
    {
        new Route($this);
        new Url(new UrlGenerator($this));
    }

    public function add(RouteDefinition $definition): void
    {
        $this->routes[strtolower($definition->getMethod()) . '@' . $definition->getPattern()] = $definition;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function remove(string $method, string $pattern): void
    {
        unset($this->routes[strtolower($method) . '@' . $pattern]);
    }
}
