<?php

declare(strict_types=1);

namespace Duyler\Router;

use Duyler\Router\Exception\RouteFileAliasIsNotStringException;
use Duyler\Router\Exception\RoutesDirPathIsNotSetException;

class RouteFilePlug
{
    private const ROUTE_DEFAULT_FILE_NAME = 'default';

    public function __construct(private string $routesDirPath, private array $aliases) {}

    public function plug(string $routesFileName): void
    {
        if (empty($this->routesDirPath)) {
            throw new RoutesDirPathIsNotSetException();
        }

        $routesFileName = $this->prepareFileAlias($routesFileName);

        if (false === is_file($this->routesDirPath . $routesFileName . '.php')) {
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
        if (false === isset($this->aliases[$routesFileName])) {
            return $routesFileName;
        }

        if (false === is_string($this->aliases[$routesFileName])) {
            throw new RouteFileAliasIsNotStringException($routesFileName, gettype($this->aliases[$routesFileName]));
        }

        return $this->aliases[$routesFileName];
    }
}
