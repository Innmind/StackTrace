<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    CallFrames,
    CallFrame,
};
use Innmind\Immutable\StreamInterface;
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

        $this->assertInstanceOf(StreamInterface::class, $frames);
        $this->assertSame(CallFrame::class, (string) $frames->type());
        $this->assertCount(17, $frames);
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames[0]);
        $this->assertInstanceOf(CallFrame\FunctionCall::class, $frames[1]);
        $this->assertInstanceOf(CallFrame\InternalFunctionCall::class, $frames[2]);
        $this->assertInstanceOf(CallFrame\FunctionCall::class, $frames[3]);
        $this->assertInstanceOf(CallFrame\StaticMethodCall::class, $frames[4]);
        $this->assertInstanceOf(CallFrame\InternalStaticMethodCall::class, $frames[5]);
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames[6]);
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames[7]);
    }

    public static function refl()
    {
        self::staticCall();
    }

    public static function staticCall()
    {
        if (!function_exists('\Tests\Innmind\StackTrace\foo')) {
            function foo(callable $x) {
                (function($x){$x();})($x);
            }
        }

        $callable = new class {
            public function __invoke()
            {
                throw new \TypeError('foo', 42);
            }
        };
        $a = [$callable];

        array_walk($a, '\Tests\Innmind\StackTrace\foo');
    }
}
