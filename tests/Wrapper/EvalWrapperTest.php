<?php

declare(strict_types=1);

namespace Kenny1911\DuckType\Test\Wrapper;

use Kenny1911\DuckType\CodeGen\ClassGenerator;
use Kenny1911\DuckType\Wrapper\EvalWrapper;
use PHPUnit\Framework\TestCase;

final class EvalWrapperTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testWrap(): void
    {
        $expectedNamespace = static::class.'\\__Eval__';
        /** @var class-string $expectedClass */
        $expectedClass = $expectedNamespace.'\\StubInterface0Impl';

        $wrapper = new EvalWrapper(new ClassGenerator('Impl', $expectedNamespace));

        $object = new class () {
            public function sum(int $a, int $b): int
            {
                return $a + $b;
            }
        };

        $wrapped = $wrapper->wrap(StubInterface::class, $object);


        $this->assertInstanceOf($expectedClass, $wrapped);
        $this->assertInstanceOf(StubInterface::class, $wrapped);
        $this->assertSame(3, $wrapped->sum(1, 2));
    }

    /**
     * @runInSeparateProcess
     */
    public function testWrapAgain(): void
    {
        $expectedNamespace = static::class.'\\__Eval__';
        /** @var class-string $expectedClass */
        $expectedClass = $expectedNamespace.'\\StubInterface0Impl';

        $wrapper = new EvalWrapper(new ClassGenerator('Impl', $expectedNamespace));

        $object = new class () {
            public function sum(int $a, int $b): int
            {
                return $a + $b;
            }
        };

        $wrapped1 = $wrapper->wrap(StubInterface::class, $object);
        $wrapped2 = $wrapper->wrap(StubInterface::class, $object);

        $this->assertInstanceOf($expectedClass, $wrapped1);
        $this->assertInstanceOf(StubInterface::class, $wrapped1);
        $this->assertSame(3, $wrapped1->sum(1, 2));

        $this->assertInstanceOf($expectedClass, $wrapped2);
        $this->assertInstanceOf(StubInterface::class, $wrapped2);
        $this->assertSame(3, $wrapped1->sum(1, 2));
    }

    public function testWrapImplementedObject(): void
    {
        $expectedNamespace = static::class.'\\__Eval__';

        $wrapper = new EvalWrapper(new ClassGenerator('Impl', $expectedNamespace));

        $object = new class () implements StubInterface {
            public function sum(int $a, int $b): int
            {
                return $a + $b;
            }
        };

        $wrapped = $wrapper->wrap(StubInterface::class, $object);

        $this->assertSame($object, $wrapped);
    }
}
