<?php

declare(strict_types=1);

namespace Kenny1911\DuckType\CodeGen;

use LogicException;
use ReflectionClass;
use ReflectionMethod;

final class ClassGenerator
{
    /**
     * @var list<string>
     */
    private static array $suffixes = [];

    private readonly string $suffix;

    private readonly string $namespace;

    private int $i = 0;

    public function __construct(string $suffix = '', string $namespace = '')
    {
        if (in_array($suffix, static::$suffixes, true)) {
            throw new LogicException($suffix ? "Suffix {$suffix} already used." : "Empty suffix already used.");
        }

        $this->suffix = $suffix;
        $this->namespace = trim($namespace, " \t\n\r\0\x0B\\");
    }

    /**
     * Return tuple of 2 elements - class name and class code.
     *
     * @return array{shortClassName: string, className: class-string, code: string}
     */
    public function generate(ReflectionClass $interface): array
    {
        if (!$interface->isInterface()) {
            throw new LogicException($interface->getName().' is not interface.');
        }

        $shortClassName = $interface->getShortName().$this->i.$this->suffix;
        /** @var class-string $className */
        $className = $this->namespace.'\\'.$shortClassName;
        ++$this->i;

        $code = $this->generateClassCode($shortClassName, $interface);

        return ['shortClassName' => $shortClassName, 'className' => $className, 'code' => $code];
    }

    private function generateClassCode(string $className, ReflectionClass $interface): string
    {
        $code = 'namespace '.$this->namespace.';';
        $code .= "class {$className} implements \\{$interface->getName()} {";
        $code .= $this->generateConstructorCode();

        foreach ($interface->getMethods() as $method) {
            $code .= $this->generateMethodCode($method);
        }

        $code .= '}';

        return $code;
    }

    private function generateConstructorCode(): string
    {
        $code = 'private readonly object $inner;';
        $code .= 'public function __construct(object $inner) {';
        $code .= '$this->inner = $inner;';
        $code .= '}';

        return $code;
    }

    private function generateMethodCode(ReflectionMethod $method): string
    {
        $code = 'public function '.$method->getName().'(';

        // Methods arguments
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->hasType()) {
                $code .= $parameter->getType().' ';
            }

            $code .= '$'.$parameter->getName().',';
        }

        $code .= ')';

        // Method return type
        if ($method->hasReturnType()) {
            $code .= ': '.$method->getReturnType();
        }

        // Method body
        $hasReturn = !in_array((string) $method->getReturnType(), ['void', 'never'], true);
        $code .= ' { '.($hasReturn ? 'return ' : '').'$this->inner->'.$method->getName().'(';

        foreach ($method->getParameters() as $parameter) {
            $code .= '$'.$parameter->getName().',';
        }

        $code .= ');} ';

        return $code;
    }
}
