<?php

declare(strict_types=1);

namespace Duyler\Router;

use Closure;

readonly class MatchedRoute
{
    public ?string $name;
    public string $pattern;
    public string|Closure|null $handler;
    public ?string $target;
    public ?string $action;
    public string $method;
    public array $where;

    public function __construct(RouteDefinition $routeDefinition)
    {
        $this->name = $routeDefinition->getName();
        $this->pattern = $routeDefinition->getPattern();
        $this->handler = $routeDefinition->getHandler();
        $this->target = $routeDefinition->getTarget();
        $this->action = $routeDefinition->getAction();
        $this->method = $routeDefinition->getMethod();
        $this->where = $routeDefinition->getWhere();
    }
}
