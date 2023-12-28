<?php

declare(strict_types=1);

namespace Duyler\Router\Exception;

use Exception;

class RouteFileAliasIsNotStringException extends Exception
{
    public function __construct(string $aliasFor, string $type)
    {
        parent::__construct('Routes file alias for ' . $aliasFor . ' will be type string. Type ' . $type . ' given.');
    }
}
