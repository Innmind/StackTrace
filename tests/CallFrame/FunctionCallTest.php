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
use Innmind\Url\UrlInterface;
use Innmind\Immutable\SequenceInterface;
use PHPUnit\Framework\TestCase;

class FunctionCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = new FunctionCall(
            $name = new FunctionName('foo'),
            $file = $this->createMock(UrlInterface::class),
            $line = new Line(42),
            'foo',
            'bar'
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertInstanceOf(UserLand::class, $frame);
        $this->assertSame($name, $frame->functionName());
        $this->assertSame($file, $frame->file());
        $this->assertSame($line, $frame->line());
        $this->assertInstanceOf(SequenceInterface::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toPrimitive());
        $this->assertSame('foo()', (string) $frame);
    }
}
