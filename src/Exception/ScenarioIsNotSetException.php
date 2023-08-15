<?php

namespace Duyler\Router\Exception;

class ScenarioIsNotSetException extends \Exception
{
    public function __construct(string $pattern)
    {
        parent::__construct('Scenario is not set for pattern "' . $pattern . '".');
    }
} 
