<?php

declare(strict_types=1);

namespace Duyler\Router\Handler;

use Duyler\Router\Exception\HandlerIsNotSetException;
use Duyler\Router\Exception\PlaceholdersForPatternNotFoundException;
use Duyler\Router\MatchedRoute;
use Duyler\Router\Request;

abstract class AbstractRouteHandler
{
    protected Request $request;
    protected null|MatchedRoute $matched = null;
    protected array $fillable = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function route(string $method, string $pattern): static
    {
        if (!is_null($this->matched)) {
            return $this;
        }

        $this->fillable = [];

        $this->fillable['method'] = $method;
        $this->fillable['pattern'] = $pattern;

        return $this;
    }

    public function where(array $where): static
    {
        if (!is_null($this->matched)) {
            return $this;
        }
        $this->fillable['where'] = $where;

        return $this;
    }

    public function name(string $name): static
    {
        if (!is_null($this->matched)) {
            return $this;
        }
        $this->fillable['name'] = $name;

        return $this;
    }

    public function handler(string $handler): static
    {
        if (!is_null($this->matched)) {
            return $this;
        }
        $this->fillable['handler'] = $handler;

        return $this;
    }

    public function scenario(string $scenario): static
    {
        if (!is_null($this->matched)) {
            return $this;
        }
        $this->fillable['scenario'] = $scenario;

        return $this;
    }

    public function action(string $action): static
    {
        if (!is_null($this->matched)) {
            return $this;
        }
        $this->fillable['action'] = $action;

        return $this;
    }

    public function match(): void
    {
        if (!is_null($this->matched)) {
            return;
        }
        $this->checkErrors();
    }

    public function isMatched(): bool
    {
        if (is_null($this->matched)) {
            return false;
        }

        return true;
    }

    protected function checkErrors(): void
    {
        if (isset($this->fillable['where']) && !preg_match('(\{\$[a-zA-Z]+\})', $this->fillable['pattern'])) {
            throw new PlaceholdersForPatternNotFoundException($this->fillable['pattern']);
        }

        if (!isset($this->fillable['handler']) and !isset($this->fillable['scenario'])) {
            throw new HandlerIsNotSetException($this->fillable['pattern']);
        }
    }
}
