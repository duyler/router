<?php

namespace Duyler\Router\Exception;

class RouteIsNotFoundForNameException extends \Exception
{
    public function __construct($routeName)
    {
        parent::__construct('Route is not found for route name "' . $routeName . '".');
    }
}
