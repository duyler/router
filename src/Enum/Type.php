<?php

namespace Duyler\Router\Enum;

enum Type: string
{
    case Integer = '([0-9]+)';
    case String = '([a-z0-9\-]+)';
    case Array = '([a-z0-9]+)\/(([a-z0-9\-]+\/)+|([a-z0-9\-_]+)+)+($)';
}
