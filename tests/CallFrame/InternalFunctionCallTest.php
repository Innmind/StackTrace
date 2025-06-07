<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\InternalFunctionCall,
    CallFrame,
    FunctionName,
};
use Innmind\Immutable\Sequence;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class InternalFunctionCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = InternalFunctionCall::of(
            $name = FunctionName::of('foo'),
            'foo',
            'bar',
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertSame($name, $frame->functionName());
        $this->assertInstanceOf(Sequence::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toList());
        $this->assertSame('foo()', $frame->toString());
    }
}
