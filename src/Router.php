<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Handler\Matcher;
use Psr\Http\Message\ServerRequestInterface;

class Router
{
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

        $matcher = new Matcher($request);

        $resolver = new Resolver(
            matcher: $matcher,
            request: $request,
            routeCollection: $routeCollection,
        );

        $resolver->setLanguages($this->routerConfig?->languages);

        return $resolver->resolve();
    }
}
