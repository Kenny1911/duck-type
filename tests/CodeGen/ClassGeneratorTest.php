<?php

declare(strict_types=1);

namespace Kenny1911\DuckType\Test\CodeGen;

use Kenny1911\DuckType\CodeGen\ClassGenerator;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

final class ClassGeneratorTest extends TestCase
{
    public function test(): void
    {
        $expectedNamespace = static::class.'\\__Eval__';
        $expectedShortClassName = 'StubInterface0Impl';
        $expectedClassName = $expectedNamespace.'\\StubInterface0Impl';

        $expectedCode = static::expectedCode($expectedNamespace, $expectedShortClassName);

        $generator = new ClassGenerator('Impl', $expectedNamespace);
        ['className' => $className, 'shortClassName' => $shortClassName, 'code' => $code] = $generator->generate(new ReflectionClass(StubInterface::class));

        $this->assertSame($expectedClassName, $className);
        $this->assertSame($expectedShortClassName, $shortClassName);
        $this->assertSame($expectedCode, $code);
    }

    public function testCounter(): void
    {
        $expectedNamespace = static::class.'\\__Eval__';

        $expectedShortClassName1 = 'StubInterface0Impl';
        $expectedClassName1 = $expectedNamespace.'\\StubInterface0Impl';
        $expectedCode1 = static::expectedCode($expectedNamespace, $expectedShortClassName1);

        $expectedShortClassName2 = 'StubInterface1Impl';
        $expectedClassName2 = $expectedNamespace.'\\StubInterface1Impl';
        $expectedCode2 = static::expectedCode($expectedNamespace, $expectedShortClassName2);


        $generator = new ClassGenerator('Impl', $expectedNamespace);

        ['className' => $className1, 'shortClassName' => $shortClassName1, 'code' => $code1] = $generator->generate(new ReflectionClass(StubInterface::class));
        ['className' => $className2, 'shortClassName' => $shortClassName2, 'code' => $code2] = $generator->generate(new ReflectionClass(StubInterface::class));

        $this->assertSame($expectedClassName1, $className1);
        $this->assertSame($expectedShortClassName1, $shortClassName1);
        $this->assertSame($expectedCode1, $code1);

        $this->assertSame($expectedClassName2, $className2);
        $this->assertSame($expectedShortClassName2, $shortClassName2);
        $this->assertSame($expectedCode2, $code2);
    }

    public static function expectedCode(string $namespace, string $shortClassName): string
    {
        $code = 'namespace '.$namespace.';';
        $code .= 'class '.$shortClassName.' implements \\'.StubInterface::class.' {';
        $code .= 'private readonly object $inner;';
        $code .= 'public function __construct(object $inner) {';
        $code .= '$this->inner = $inner;';
        $code .= '}';
        $code .= 'public function foo($foo,) { return $this->inner->foo($foo,);} ';
        $code .= 'public function bar(?int $bar,): void { $this->inner->bar($bar,);} ';
        $code .= 'public function baz(string|int|null $a,mixed $b,): string { return $this->inner->baz($a,$b,);} ';
        $code .= '}';

        return $code;
    }
}
