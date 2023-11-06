<?php

declare(strict_types=1);

namespace Kenny1911\DuckType;

use Kenny1911\DuckType\CodeGen\ClassGenerator;
use Kenny1911\DuckType\Wrapper\EvalWrapper;

/**
 * @template T of object
 * @param class-string<T> $interface
 * @param object $object
 * @return T
 */
function duck_type(string $interface, object $object): object
{
    static $wrapper = new EvalWrapper(
        new ClassGenerator('Impl', EvalWrapper::class.'\\__Eval__')
    );

    return $wrapper->wrap($interface, $object);
}
