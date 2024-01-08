<?php

declare(strict_types=1);

namespace Duyler\Router;

readonly class RouterConfig
{
    public function __construct(
        public array $languages = [],
    ) {}
}
