<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Handler\Mapper;
use Duyler\Router\Handler\UrlGenerator;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private Resolver $resolver;

    public function __construct(ServerRequestInterface $serverRequest, RouterConfig $routerConfig)
    {
        $request = new Request(
            $serverRequest->getUri()->getPath(),
            $serverRequest->getMethod(),
            $serverRequest->getUri()->getHost(),
            $serverRequest->getUri()->getScheme(),
        );

        $routerFilePlug = new RouteFilePlug(
            $routerConfig->routesDirPath,
            $routerConfig->routesAliases,
        );

        $mapper = new Mapper($request);

        $this->resolver = new Resolver(
            $mapper,
            $request,
            $routerFilePlug,
            new Result()
        );

        new Route($mapper);
        new Url(new UrlGenerator($routerFilePlug, $request));
    }

    public static function create(ServerRequestInterface $serverRequest, RouterConfig $routerConfig): static
    {
        return new static($serverRequest, $routerConfig);
    }

    public function startRouting(): Result
    {
        return $this->resolver->resolve();
    }
}
