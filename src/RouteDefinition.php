<?php

declare(strict_types=1);

namespace Duyler\Router;

class RouteDefinition
{
    public function __construct(
        private readonly string $method,
        private readonly string $pattern,
        private string $name = '',
        private string $handler = '',
        private string $scenario = '',
        private string $action = '',
        private array $where = [],
    ) {}

    public function name(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function handler(string $handler): static
    {
        $this->handler = $handler;

        return $this;
    }

    public function scenario(string $scenario): static
    {
        $this->scenario = $scenario;

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

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getScenario(): string
    {
        return $this->scenario;
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
