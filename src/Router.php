<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Handler\Mapper;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
    private Resolver $resolver;
    private RouteCollection $routeCollection;

    public function __construct(
        private ?RouterConfig $routerConfig = null,
    ) {}

    public function startRouting(RouteCollection $routeCollection, ServerRequestInterface $serverRequest): CurrentRoute
    {
        $request = new Request(
            $serverRequest->getUri()->getPath(),
            $serverRequest->getMethod(),
            $serverRequest->getUri()->getHost(),
            $serverRequest->getUri()->getScheme(),
        );

        $mapper = new Mapper($request);

        $this->resolver = new Resolver(
            mapper: $mapper,
            request: $request,
            routeCollection: $routeCollection,
        );

        $this->resolver->setLanguages($this->routerConfig?->languages);

        return $this->resolver->resolve();
    }
}
