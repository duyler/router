<?php

declare(strict_types=1);

namespace Duyler\Router\Exception;

use Exception;

class RouterIsNotInitializedException extends Exception
{
    public function __construct()
    {
        parent::__construct('Router is not initialized');
    }
}
