<?php

namespace Duyler\Router\Exception;

class HandlerIsNotSetException extends \Exception
{
    public function __construct(string $pattern)
    {
        parent::__construct('Handler is not set for pattern "' . $pattern . '".');
    }
}
