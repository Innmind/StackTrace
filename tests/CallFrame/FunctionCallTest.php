<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\FunctionCall,
    CallFrame\UserLand,
    CallFrame,
    FunctionName,
    Line,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;
use PHPUnit\Framework\TestCase;

class FunctionCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = FunctionCall::of(
            $name = FunctionName::of('foo'),
            $file = Url::of('http://example.com'),
            $line = Line::of(42),
            'foo',
            'bar',
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertInstanceOf(UserLand::class, $frame);
        $this->assertSame($name, $frame->functionName());
        $this->assertSame($file, $frame->file());
        $this->assertSame($line, $frame->line());
        $this->assertInstanceOf(Sequence::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toList());
        $this->assertSame('foo()', $frame->toString());
    }
}
