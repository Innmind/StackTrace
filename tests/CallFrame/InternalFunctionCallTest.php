<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\InternalFunctionCall,
    CallFrame\FunctionName,
    CallFrame,
};
use Innmind\Immutable\SequenceInterface;
use PHPUnit\Framework\TestCase;

class InternalFunctionCallTest extends TestCase
{
    public function testInterface()
    {
        $frame = new InternalFunctionCall(
            $name = new FunctionName('foo'),
            'foo',
            'bar'
        );

        $this->assertInstanceOf(CallFrame::class, $frame);
        $this->assertSame($name, $frame->functionName());
        $this->assertInstanceOf(SequenceInterface::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], $frame->arguments()->toPrimitive());
        $this->assertSame('foo()', (string) $frame);
    }
}
