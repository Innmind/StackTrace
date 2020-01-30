<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\InternalFunctionCall,
    CallFrame,
    FunctionName,
};
use Innmind\Immutable\Sequence;
use function Innmind\Immutable\unwrap;
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
        $this->assertInstanceOf(Sequence::class, $frame->arguments());
        $this->assertSame(['foo', 'bar'], unwrap($frame->arguments()));
        $this->assertSame('foo()', $frame->toString());
    }
}
