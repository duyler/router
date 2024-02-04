<?php

namespace Duyler\Router\Exception;

use Exception;

class HandlerIsNotSetException extends Exception
{
    public function __construct(string $pattern)
    {
        parent::__construct('Action, handler and target is not set for pattern "' . $pattern . '".');
    }
}
