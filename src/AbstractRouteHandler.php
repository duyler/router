<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Exception\PlaceholdersForPatternNotFoundException;
use Duyler\Router\Exception\HandlerIsNotSetException;
use Duyler\Router\Exception\ScenarioIsNotSetException;

abstract class AbstractRouteHandler
{
    protected Request $request;
    protected MatchedRoute|null $matched = null;
    protected array $fillable = [];
    protected array $attributeTypes = [
        'STRING' => '([a-z0-9\-]+)',
        'INTEGER' => '([0-9]+)',
        'ARRAY' => '([a-z0-9]+)/(([a-z0-9\-]+/)+|([a-z0-9\-_]+)+)($)',
    ];
    
    const PLACEHOLDER_TYPE_STRING = 'STRING';
    const PLACEHOLDER_TYPE_INTEGER = 'INTEGER';
    const PLACEHOLDER_TYPE_ARRAY = 'ARRAY';

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
        if (!is_null($where)) {
            $this->fillable['where'] = $where;
        }
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
    
    public function getPlaceholderRegExp(string $type): string
    {
        return $this->attributeTypes[strtoupper($type)];
    }

    public function hasPlaceholderType(string $type): bool
    {
        return array_key_exists(strtoupper($type), $this->attributeTypes);
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
