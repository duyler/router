<?php

namespace Duyler\Router\Exception;

class HandlerIsNotSetException extends \Exception
{
    public function __construct(string $pattern)
    {
        parent::__construct('Handler or scenario is not set for pattern "' . $pattern . '".');
    }
}
