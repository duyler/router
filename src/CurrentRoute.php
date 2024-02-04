<?php

declare(strict_types=1);

namespace Duyler\Router;

use Closure;

class CurrentRoute
{
    public bool $status = false;
    public string|Closure $handler = '';
    public string $target = '';
    public string $action = '';
    public array $attributes = [];
    public string $language = '';
}
