<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\StaticMethodCall,
    CallFrame\UserLand,
    CallFrame,
    ClassName,
    Method,
    Line,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\SequenceInterface;
use PHPUnit\Framework\TestCase;

class StaticMethodCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = new StaticMethodCall(
            $class = new ClassName('foo'),
            $method = new Method('bar'),
            $file = $this->createMock(UrlInterface::class),
            $line = new Line(42),
            'foo',
            'bar'
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertInstanceOf(UserLand::class, $frame);
        $this->assertSame($class, $frame->class());
        $this->assertSame($method, $frame->method());
        $this->assertSame($file, $frame->file());
        $this->assertSame($line, $frame->line());
        $this->assertInstanceOf(SequenceInterface::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toPrimitive());
        $this->assertSame('foo::bar()', (string) $frame);
    }
}
