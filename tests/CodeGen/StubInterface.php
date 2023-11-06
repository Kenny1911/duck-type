<?php

declare(strict_types=1);

namespace Kenny1911\DuckType\Test\CodeGen;

interface StubInterface
{
    /**
     * @psalm-suppress MissingParamType
     * @psalm-suppress MissingReturnType
     */
    public function foo($foo);

    public function bar(?int $bar): void;

    public function baz(int|string|null $a, mixed $b): string;
}
