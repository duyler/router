<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Enum\Type;
use Duyler\Router\Handler\Matcher;

class Resolver
{
    private const string ATTRIBUTE_ARRAY_DELIMITER = '/';
    private array $languages = [];

    public function __construct(
        private readonly Matcher $matcher,
        private readonly Request $request,
        private readonly RouteCollection $routeCollection,
    ) {}

    public function resolve(): CurrentRoute
    {
        $language = '';

        if (count($this->languages) > 0) {
            $language = $this->prepareLanguage($this->request->getUri());
        }

        $matched = $this->matchRoute();

        if ($matched === null) {
            return new CurrentRoute();
        }

        $result = new CurrentRoute();
        $result->status = true;
        $result->handler = $matched->handler;
        $result->target = $matched->target;
        $result->action = $matched->action;
        $result->language = $language;

        if (!empty($matched->where)) {
            $where = $this->prepareWhere($matched->where, $matched->pattern);
            $attributesTypesMap = $this->prepareTypes($matched->where);
            $result->attributes = $this->assignAttributes($where, $matched->pattern, $attributesTypesMap);
        }

        return $result;
    }

    public function setLanguages(array $languages = []): void
    {
        $this->languages = $languages;
    }

    private function matchRoute(): ?MatchedRoute
    {
        foreach ($this->routeCollection->getRoutes() as $routeDefinition) {
            if ($this->matcher->match($routeDefinition)) {
                return $this->matcher->getMatched();
            }
        }
        return null;
    }

    private function prepareLanguage(string $baseUri): string
    {
        $currentLanguage = '';

        $segments = explode('/', $baseUri);

        if (in_array($segments[0], $this->languages, true)) {
            $currentLanguage = array_shift($segments);
        }

        $uri = trim(implode('/', $segments), '/');

        if (empty($uri)) {
            $uri = '/';
        }

        $this->request->replaceUri($uri);

        return $currentLanguage;
    }

    private function assignAttributes(array $where, string $pattern, array $attributesTypesMap): array
    {
        $pattern = trim($pattern, '/');

        $rawAttributes = [];

        $uri = $this->request->getUri();

        $patternWithoutPlaceholders = $this->clearPlaceholdersInPattern($where, $pattern);

        $segments = explode(' ', trim($patternWithoutPlaceholders, ' '));

        foreach ($where as $key => $value) {
            $needless = array_shift($segments);

            $uri = substr($uri, strlen($needless));

            $delimiter = str_starts_with($value, '(') ? ')' : substr($value, 0, 1);

            $segmentPattern = $this->makePattern($segments, $delimiter, $value);

            preg_match("/{$segmentPattern}/m", $uri, $matched);

            if (empty($segments)) {
                $rawAttributes[$key] = $matched[0];
            } else {
                $rawAttributes[$key] = substr($matched[0], 0, -strlen(current($segments)));
            }

            $uri = substr($uri, strlen($rawAttributes[$key]));
        }

        return $this->typeConversion($rawAttributes, $attributesTypesMap);
    }

    private function clearPlaceholdersInPattern(array $where, string $pattern): string
    {
        while (null !== key($where)) {
            $pattern = str_replace('{$' . key($where) . '}', ' ', $pattern);
            next($where);
        }

        return $pattern;
    }

    private function makePattern(array $segments, string $delimiter, string $whereValue): string
    {
        if (false === isset($segments[0])) {
            return substr($whereValue, 0, -1) . $delimiter;
        }

        return substr($whereValue, 0, -1) . preg_quote($segments[0]) . $delimiter;
    }

    private function prepareWhere(array $where, string $pattern): array
    {
        preg_match_all('(\{\$[a-zA-Z]+\})', $pattern, $matched, PREG_SET_ORDER);

        $sortedWhere = [];

        foreach ($matched as $value) {
            $placeHolder = substr($value[0], 2, -1);

            $sortedWhere[$placeHolder] = match ($where[$placeHolder]) {
                Type::Integer => Type::Integer->value,
                Type::String => Type::String->value,
                Type::Array => Type::Array->value,
                default => $where[$placeHolder],
            };
        }

        return $sortedWhere;
    }

    private function prepareTypes(array $where): array
    {
        $attributesTypesMap = [];

        foreach ($where as $placeHolder => $value) {
            $attributesTypesMap[$placeHolder] = match ($value) {
                Type::Integer => Type::Integer,
                Type::String => Type::String,
                Type::Array => Type::Array,
            };
        }

        return $attributesTypesMap;
    }

    private function typeConversion(array $rawAttributes, array $attributesTypesMap): array
    {
        $attributes = [];

        if (empty($attributesTypesMap)) {
            return $rawAttributes;
        }

        foreach ($rawAttributes as $placeHolder => $value) {
            $attributes[$placeHolder] = match ($attributesTypesMap[$placeHolder]) {
                Type::Integer => intval($value),
                Type::String => strval($value),
                Type::Array => explode(self::ATTRIBUTE_ARRAY_DELIMITER, $value),
                default => $value,
            };
        }

        return $attributes;
    }
}
