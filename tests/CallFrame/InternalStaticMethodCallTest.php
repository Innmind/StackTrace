<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\InternalStaticMethodCall,
    CallFrame,
    ClassName,
    Method,
};
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class InternalStaticMethodCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = new InternalStaticMethodCall(
            $class = new ClassName('foo'),
            $method = new Method('bar'),
            'foo',
            'bar'
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertSame($class, $frame->class());
        $this->assertSame($method, $frame->method());
        $this->assertInstanceOf(Sequence::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toList());
        $this->assertSame('foo::bar()', $frame->toString());
    }
}
