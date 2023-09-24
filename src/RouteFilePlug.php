<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Exception\RoutesDirPathIsNotSetException;
use Duyler\Router\Exception\RouteFileAliasIsNotStringException;

class RouteFilePlug
{
    private const ROUTE_DEFAULT_FILE_NAME = 'default';

    public function __construct(private string $routesDirPath, private array $aliases)
    {
    }

    public function plug(string $routesFileName): void
    {
        if (empty($this->routesDirPath)) {
            throw new RoutesDirPathIsNotSetException();
        }

        $routesFileName = $this->prepareFileAlias($routesFileName);

        if (is_file($this->routesDirPath . $routesFileName . '.php') === false) {
            $this->plugDefault();
            return;
        }

        require $this->routesDirPath . $routesFileName . '.php';
    }

    public function plugDefault(): void
    {
        if (empty($this->routesDirPath)) {
            throw new RoutesDirPathIsNotSetException();
        }
        require $this->routesDirPath . self::ROUTE_DEFAULT_FILE_NAME . '.php';
    }

    private function prepareFileAlias(string $routesFileName): string
    {
        if (isset($this->aliases[$routesFileName]) === false) {
            return $routesFileName;
        }

        if (is_string($this->aliases[$routesFileName]) === false) {
            throw new RouteFileAliasIsNotStringException($routesFileName, gettype($this->aliases[$routesFileName]));
        }
        return $this->aliases[$routesFileName];
    }
}
