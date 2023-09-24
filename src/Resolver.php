<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Enum\Type;
use Duyler\Router\Handler\Mapper;

class Resolver
{
    private array $segments = [];
    private array $languages = [];
    private string $uri;

    private const ATTRIBUTE_ARRAY_DELIMITER = '/';

    public function __construct(
        private readonly Mapper $mapper,
        private readonly Request $request,
        private readonly RouteFilePlug $routeFilePlug,
        private readonly Result $result
    ) {
    }

    public function resolve(): Result
    {
        $this->uri = $this->request->getUri();

        $this->segments = explode('/', $this->uri);

        if (count($this->languages) > 0) {
            $this->result->language = $this->prepareLanguage();
        }

        $this->plugRoutes();

        return $this->buildResult();
    }

    public function setLanguages(array $languages = []): void
    {
        $this->languages = $languages;
    }

    private function plugRoutes(): void
    {
        if ($this->uri === '/' or count($this->segments) === 0) {
            $this->routeFilePlug->plugDefault();
        } else {
            $this->routeFilePlug->plug($this->segments[0]);
        }
    }

    private function buildResult(): Result
    {
        if (!$this->mapper->isMatched()) {
            return $this->result;
        }

        $matched = $this->mapper->getMatched();

        $this->result->status = true;
        $this->result->handler = $matched->handler;
        $this->result->scenario = $matched->scenario;
        $this->result->action = $matched->action;

        if (!empty($matched->where)) {
            $where = $this->prepareWhere($matched->where, $matched->pattern);
            $attributesTypesMap = $this->prepareTypes($matched->where);
            $this->result->attributes = $this->assignAttributes($where, $matched->pattern, $attributesTypesMap);
        }

        return $this->result;
    }

    private function prepareLanguage(): string
    {
        $currentLanguage = '';

        if (in_array($this->segments[0], $this->languages, true)) {
            $currentLanguage = array_shift($this->segments);
        }

        $uri = trim(str_replace($this->languages, '', $this->uri), '/');

        if (empty($uri)) {
            $uri = '/';
        }

        $this->request->replaceUri($uri);

        return $currentLanguage;
    }

    private function assignAttributes(array $where, string $pattern, array $attributesTypesMap): array
    {
        $rawAttributes = [];

        $uri = $this->request->getUri();

        $patternWithoutPlaceholders = $this->clearPlaceholdersInPattern($where, $pattern);

        $segments = explode(' ', trim($patternWithoutPlaceholders, ' '));

        foreach ($where as $key => $value) {

            $needless = array_shift($segments);

            $uri = substr($uri, strlen($needless));

            $delimiter = substr($value, 0, 1) === '(' ? ')' : substr($value, 0, 1);

            $segmentPattern = $this->makePattern($segments, $delimiter, $value);

            preg_match($segmentPattern, $uri, $matched);

            if (empty($segments)) {
                $rawAttributes[$key] = $matched[0];
            } else {
                $rawAttributes[$key] = substr($matched[0], 0, -(strlen(current($segments))));
            }

            $uri = substr($uri, strlen($rawAttributes[$key]));
        }

        return $this->typeConversion($rawAttributes, $attributesTypesMap);
    }

    private function clearPlaceholdersInPattern(array $where, string $pattern): string
    {
        while (key($where) !== null) {
            $pattern = str_replace('{$' . key($where) . '}', ' ', $pattern);
            next($where);
        }
        return $pattern;
    }

    private function makePattern(array $segments, string $delimiter, string $whereValue): string
    {
        if (isset($segments[0]) === false) {
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
