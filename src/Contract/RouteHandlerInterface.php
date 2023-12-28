<?php

namespace Duyler\Router\Contract;

interface RouteHandlerInterface
{
    public function route(string $method, string $pattern): static;

    public function where(array $where): static;

    public function name(string $name): static;

    public function handler(string $handler): static;

    public function scenario(string $scenario): static;

    public function action(string $action): static;

    public function match(): void;
}
