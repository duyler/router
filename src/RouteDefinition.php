<?php

declare(strict_types=1);

namespace Duyler\Router;

use Closure;

class RouteDefinition
{
    public function __construct(
        private readonly string $method,
        private readonly string $pattern,
        private string $name = '',
        private string|Closure|null $handler = null,
        private string $target = '',
        private string $action = '',
        private array $where = [],
    ) {}

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function handler(mixed $handler): static
    {
        $this->handler = $handler;

        return $this;
    }

    public function target(string $target): static
    {
        $this->target = $target;

        return $this;
    }

    public function action(string $action): static
    {
        $this->action = $action;

        return $this;
    }

    public function where(array $where): static
    {
        $this->where = $where;

        return $this;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHandler(): mixed
    {
        return $this->handler;
    }

    public function getTarget(): string
    {
        return $this->target;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getWhere(): array
    {
        return $this->where;
    }
}
