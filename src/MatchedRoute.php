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

    public static function create(array $fillable): self
    {
        $matched = new static();
        $matched->name = $fillable['name'] ?? '';
        $matched->pattern = $fillable['pattern'];
        $matched->handler = $fillable['handler'] ?? '';
        $matched->scenario = $fillable['scenario'] ?? '';
        $matched->action = $fillable['action'] ?? '';
        $matched->method = $fillable['method'];
        $matched->where = $fillable['where'] ?? [];
        return $matched;
    }
}
