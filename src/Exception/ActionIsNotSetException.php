<?php

namespace Duyler\Router\Exception;

class ActionIsNotSetException extends \Exception
{
    public function __construct(string $pattern)
    {
        parent::__construct('Action is not set for pattern "' . $pattern . '".');
    }
} 
