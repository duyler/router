<?php

declare(strict_types=1);

namespace Duyler\Router\Handler;

use Duyler\Router\Contract\RouteHandlerInterface;
use Duyler\Router\Enum\Type;
use Duyler\Router\MatchedRoute;

class Mapper extends AbstractRouteHandler implements RouteHandlerInterface
{
    public function match(): void
    {
        parent::match();

        if (!is_null($this->matched)) {
            return;
        }

        if (!$this->request->isMethod(strtoupper($this->fillable['method']))) {
            return;
        }

        $pattern = $this->fillable['pattern'];

        // @todo добавить проверку на существование плейсхолдера с выбросом исключения
        if (!empty($this->fillable['where'])) {
            foreach ($this->fillable['where'] as $key => $condition) {
                $pattern = match ($condition) {
                    Type::Integer => str_replace('{$' . $key . '}', Type::Integer->value, $pattern),
                    Type::String => str_replace('{$' . $key . '}', Type::String->value, $pattern),
                    Type::Array => str_replace('{$' . $key . '}', Type::Array->value, $pattern),
                    default => str_replace('{$' . $key . '}', $condition, $pattern),
                };
            }
        }

        if (!preg_match("(^$pattern$)", $this->request->getUri())) {
            return;
        }

        $this->matched = MatchedRoute::create($this->fillable);
    }

    public function getMatched(): MatchedRoute
    {
        return $this->matched;
    }
}