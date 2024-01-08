<?php

declare(strict_types=1);

namespace Duyler\Router;

readonly class MatchedRoute
{
    public string $name;
    public string $pattern;
    public string $handler;
    public string $scenario;
    public string $action;
    public string $method;
    public array $where;

    public function __construct(RouteDefinition $routeDefinition)
    {
        $this->name = $routeDefinition->getName() ?? '';
        $this->pattern = $routeDefinition->getPattern();
        $this->handler = $routeDefinition->getHandler() ?? '';
        $this->scenario = $routeDefinition->getScenario() ?? '';
        $this->action = $routeDefinition->getAction() ?? '';
        $this->method = $routeDefinition->getMethod();
        $this->where = $routeDefinition->getWhere() ?? [];
    }
}
