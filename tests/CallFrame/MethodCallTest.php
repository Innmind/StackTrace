<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\MethodCall,
    CallFrame\ClassName,
    CallFrame\Method,
    CallFrame\Line,
    CallFrame,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\SequenceInterface;
use PHPUnit\Framework\TestCase;

class MethodCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = new MethodCall(
            $class = new ClassName('foo'),
            $method = new Method('bar'),
            $file = $this->createMock(UrlInterface::class),
            $line = new Line(42),
            'foo',
            'bar'
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertSame($class, $frame->class());
        $this->assertSame($method, $frame->method());
        $this->assertSame($file, $frame->file());
        $this->assertSame($line, $frame->line());
        $this->assertInstanceOf(SequenceInterface::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toPrimitive());
        $this->assertSame('foo->bar()', (string) $frame);
    }
}
