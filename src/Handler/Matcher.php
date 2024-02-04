<?php

declare(strict_types=1);

namespace Duyler\Router\Handler;

use Duyler\Router\Enum\Type;
use Duyler\Router\Exception\HandlerIsNotSetException;
use Duyler\Router\Exception\PlaceholdersForPatternNotFoundException;
use Duyler\Router\MatchedRoute;
use Duyler\Router\Request;
use Duyler\Router\RouteDefinition;

class Matcher
{
    protected MatchedRoute $matched;

    public function __construct(private Request $request) {}

    public function match(RouteDefinition $routeDefinition): bool
    {
        $this->checkErrors($routeDefinition);

        if (!$this->request->isMethod(strtoupper($routeDefinition->getMethod()))) {
            return false;
        }

        $pattern = $routeDefinition->getPattern();

        // @todo добавить проверку на существование плейсхолдера с выбросом исключения
        if (!empty($routeDefinition->getWhere())) {
            foreach ($routeDefinition->getWhere() as $key => $condition) {
                $pattern = match ($condition) {
                    Type::Integer => str_replace('{$' . $key . '}', Type::Integer->value, $pattern),
                    Type::String => str_replace('{$' . $key . '}', Type::String->value, $pattern),
                    Type::Array => str_replace('{$' . $key . '}', Type::Array->value, $pattern),
                    default => str_replace('{$' . $key . '}', $condition, $pattern),
                };
            }
        }

        if (!preg_match("(^{$pattern}$)", $this->request->getUri())) {
            return false;
        }

        $this->matched = new MatchedRoute($routeDefinition);
        return true;
    }

    public function getMatched(): MatchedRoute
    {
        return $this->matched;
    }

    protected function checkErrors(RouteDefinition $routeDefinition): void
    {
        if ($routeDefinition->getWhere() && !preg_match('(\{\$[a-zA-Z]+\})', $routeDefinition->getPattern())) {
            throw new PlaceholdersForPatternNotFoundException($routeDefinition->getPattern());
        }

        if (!$routeDefinition->getAction() and !$routeDefinition->getHandler() and !$routeDefinition->getTarget()) {
            throw new HandlerIsNotSetException($routeDefinition->getPattern());
        }
    }
}
