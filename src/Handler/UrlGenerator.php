<?php

declare(strict_types=1);

namespace Duyler\Router\Handler;

use Duyler\Router\Exception\PlaceholderIsNotFoundForRouteException;
use Duyler\Router\Exception\PlaceholdersParamsIsNotFoundException;
use Duyler\Router\Exception\RouteIsNotFoundForNameException;
use Duyler\Router\RouteCollection;
use Duyler\Router\RouteDefinition;

class UrlGenerator
{
    public function __construct(private RouteCollection $routeCollection) {}

    public function getUrl(string $routeName, array $params = [], string $lang = ''): string
    {
        foreach ($this->routeCollection->getRoutes() as $route) {
            if ($route->getName() === $routeName) {
                return $this->buildUrl($route, $params, $lang);
            }
        }

        throw new RouteIsNotFoundForNameException($routeName);
    }

    private function buildUrl(RouteDefinition $route, array $params, string $lang): string
    {
        $pattern = $route->getPattern();

        if (preg_match('(\$[a-z]+)', $pattern) && empty($params)) {
            throw new PlaceholdersParamsIsNotFoundException($pattern);
        }

        foreach ($params as $placeholderSelector => $value) {
            $placeholder = ('{$' . $placeholderSelector . '}');

            if (!preg_match('(' . preg_quote($placeholder) . ')', $pattern)) {
                throw new PlaceholderIsNotFoundForRouteException(
                    $placeholderSelector,
                    $pattern,
                    $route->getName()
                );
            }

            $pattern = str_replace($placeholder, $value, $pattern);
        }

        $uri = trim($route->getPattern(), '/');

        if (!empty($lang)) {
            $uri = $lang . '/' . $uri;
        }

        return '/' . $uri;
    }
}
