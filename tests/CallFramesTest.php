<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    CallFrames,
    CallFrame,
};
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class CallFramesTest extends TestCase
{
    public function testOf()
    {
        try {
            $refl = new \ReflectionMethod(self::class, 'refl');
            $refl->invoke($this);
        } catch (\TypeError $e) {
            $frames = CallFrames::of($e);
        }

        $this->assertInstanceOf(Sequence::class, $frames);
        $this->assertSame(CallFrame::class, $frames->type());
        $this->assertCount(18, $frames);
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames->get(0));
        $this->assertInstanceOf(CallFrame\FunctionCall::class, $frames->get(1));
        $this->assertInstanceOf(CallFrame\InternalFunctionCall::class, $frames->get(2));
        $this->assertInstanceOf(CallFrame\FunctionCall::class, $frames->get(3));
        $this->assertInstanceOf(CallFrame\StaticMethodCall::class, $frames->get(4));
        $this->assertInstanceOf(CallFrame\InternalStaticMethodCall::class, $frames->get(5));
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames->get(6));
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames->get(7));
    }

    public static function refl()
    {
        self::staticCall();
    }

    public static function staticCall()
    {
        if (!\function_exists('\Tests\Innmind\StackTrace\foo')) {
            function foo(callable $x)
            {
                (static function($x) {$x(); })($x);
            }
        }

        $callable = new class {
            public function __invoke()
            {
                throw new \TypeError('foo', 42);
            }
        };
        $a = [$callable];

        \array_walk($a, '\Tests\Innmind\StackTrace\foo');
    }
}
