<?php

declare(strict_types=1);

namespace Kenny1911\DuckType\Wrapper;

interface Wrapper
{
    /**
     * @template T of object
     * @param class-string<T> $interface
     * @param object $object
     * @return T
     */
    public function wrap(string $interface, object $object): object;
}
