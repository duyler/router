<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Handler\UrlGenerator;

class RouteCollection
{
    public function __construct()
    {
        new Route($this);
        new Url(new UrlGenerator($this));
    }

    /** @var RouteDefinition[]  */
    private array $routes = [];

    public function add(RouteDefinition $definition): void
    {
        $this->routes[] = $definition;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }
}
