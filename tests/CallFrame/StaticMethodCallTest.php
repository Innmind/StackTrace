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
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class StaticMethodCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = StaticMethodCall::of(
            $class = ClassName::of('foo'),
            $method = Method::of('bar'),
            $file = Url::of('http://example.com'),
            $line = Line::of(42),
            'foo',
            'bar',
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertInstanceOf(UserLand::class, $frame);
        $this->assertSame($class, $frame->class());
        $this->assertSame($method, $frame->method());
        $this->assertSame($file, $frame->file());
        $this->assertSame($line, $frame->line());
        $this->assertInstanceOf(Sequence::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toList());
        $this->assertSame('foo::bar()', $frame->toString());
    }
}
