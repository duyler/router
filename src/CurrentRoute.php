<?php

declare(strict_types=1);

namespace Duyler\Router;

use Closure;

readonly class CurrentRoute
{
    public function __construct(
        public bool $status = false,
        public string|Closure|null $handler = null,
        public ?string $target = null,
        public ?string $action = null,
        public array $attributes = [],
        public ?string $language = null,
    ) {}
}
