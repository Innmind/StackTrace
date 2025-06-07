<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\InternalMethodCall,
    CallFrame,
    ClassName,
    Method,
};
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class InternalMethodCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = InternalMethodCall::of(
            $class = ClassName::of('foo'),
            $method = Method::of('bar'),
            'foo',
            'bar',
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertSame($class, $frame->class());
        $this->assertSame($method, $frame->method());
        $this->assertInstanceOf(Sequence::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toList());
        $this->assertSame('foo->bar()', $frame->toString());
    }
}
