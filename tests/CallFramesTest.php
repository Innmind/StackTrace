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
            self::staticCall();
        } catch (\TypeError $e) {
            $frames = CallFrames::of($e);
        }

        $this->assertInstanceOf(StreamInterface::class, $frames);
        $this->assertSame(CallFrame::class, (string) $frames->type());
        $this->assertCount(16, $frames);
        $frames = $frames->take(6); // up to the caller of this test
        $this->assertInstanceOf(CallFrame\MethodCall::class, $frames[0]);
        $this->assertInstanceOf(CallFrame\FunctionCall::class, $frames[1]);
        $this->assertInstanceOf(CallFrame\InternalFunctionCall::class, $frames[2]);
        $this->assertInstanceOf(CallFrame\FunctionCall::class, $frames[3]);
        $this->assertInstanceOf(CallFrame\StaticMethodCall::class, $frames[4]);
        $this->assertInstanceOf(CallFrame\InternalMethodCall::class, $frames[5]);
    }

    public static function staticCall()
    {
        function foo(callable $x) {
            (function($x){$x();})($x);
        }

        $callable = new class {
            public function __invoke()
            {
                throw new \TypeError;
            }
        };
        $a = [$callable];

        array_walk($a, '\Tests\Innmind\StackTrace\foo');
    }
}
