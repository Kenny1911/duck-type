<?php

declare(strict_types=1);

namespace Kenny1911\DuckType\Wrapper;

use Kenny1911\DuckType\CodeGen\ClassGenerator;
use ReflectionClass;

final class EvalWrapper implements Wrapper
{
    private readonly ClassGenerator $generator;

    /**
     * @var array<class-string, class-string>
     */
    private array $classes = [];

    public function __construct(ClassGenerator $generator)
    {
        $this->generator = $generator;
    }

    /**
     * @template T of object
     * @param class-string<T> $interface
     * @param object $object
     * @return T
     */
    public function wrap(string $interface, object $object): object
    {
        if (is_a($object, $interface)) {
            return $object;
        }

        if (!isset($this->classes[$interface])) {
            ['className' => $className, 'code' => $code] = $this->generator->generate(new ReflectionClass($interface));
            eval($code);

            $this->classes[$interface] = $className;
        }

        /** @var class-string<T> $className */
        $className = $this->classes[$interface];

        /** @psalm-suppress MixedMethodCall */
        return new $className($object);
    }
}
