<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\InternalStaticMethodCall,
    CallFrame\ClassName,
    CallFrame\Method,
    CallFrame,
};
use Innmind\Immutable\SequenceInterface;
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
        $this->assertInstanceOf(SequenceInterface::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toPrimitive());
        $this->assertSame('foo::bar()', (string) $frame);
    }
}
