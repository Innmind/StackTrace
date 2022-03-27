<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    StackTrace,
    Throwable,
};
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class StackTraceTest extends TestCase
{
    public function testInterface()
    {
        $stack = StackTrace::of(
            $foo = new \RuntimeException(
                'foo',
                24,
                $bar = new \LogicException(
                    'bar',
                    42,
                    $baz = new \Exception(
                        'baz',
                        66,
                    ),
                ),
            ),
        );

        $this->assertEquals(Throwable::of($foo), $stack->throwable());
        $this->assertInstanceOf(Sequence::class, $stack->previous());
        $this->assertCount(2, $stack->previous());
        $this->assertEquals(
            [Throwable::of($bar), Throwable::of($baz)],
            $stack->previous()->toList(),
        );
    }
}
