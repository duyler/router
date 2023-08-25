<?php

declare(strict_types=1);

namespace Duyler\Router;

class Result
{
    public bool $status = false;
    public string $handler = '';
    public string $scenario = '';
    public string $action = '';
    public array $attributes = [];
    public string $language = '';
}
