<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\DependencyInjection\ContainerBuilder;

class Router
{
    private Resolver $resolver;
    private RouteFilePlug $routeFilePlug;

    public function __construct(
        Resolver $resolver,
        UrlGenerator $urlGenerator,
        Mapper $mapper,
        RouteFilePlug $routeFilePlug)
    {
        $this->resolver = $resolver;
        $this->routeFilePlug = $routeFilePlug;
        Route::setHandler($mapper);
        Url::setUrlGenerator($urlGenerator);
    }

    public static function create(string $uri, string $method, string $host, string $protocol = 'http'): self
    {
        $container = ContainerBuilder::build();
        $container->set(new Request($uri, $method, $host, $protocol));

        return $container->make(static::class);
    }

    public function setRoutesDirPath(string $dirPath): self
    {
        $this->routeFilePlug->setRoutesDirPath($dirPath);
        return $this;
    }

    public function setRoutesAliases(array $aliases): self
    {
        $this->routeFilePlug->setRoutesAliases($aliases);
        return $this;
    }
    
    public function setLanguages(array $languages): self
    {
        $this->resolver->setLanguages($languages);
        return $this;
    }

    public function startRouting(): Result
    {
        return $this->resolver->resolve();
    }
}
