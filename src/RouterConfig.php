<?php

declare(strict_types=1);

namespace Duyler\Router;

readonly class RouterConfig
{
    public function __construct(
        public string $routesDirPath,
        public array  $routesAliases = [],
        public array  $languages = [],
    ) {
    }
}
