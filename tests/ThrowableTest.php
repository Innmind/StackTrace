<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    Throwable,
    CallFrame,
    ClassName,
    Line,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    StreamInterface,
    Str,
};
use PHPUnit\Framework\TestCase;

class ThrowableTest extends TestCase
{
    public function testInterface()
    {
        $throwable = new Throwable($e = new \Exception('foo', 42));

        $this->assertInstanceOf(ClassName::class, $throwable->class());
        $this->assertSame(\Exception::class, (string) $throwable->class());
        $this->assertInstanceOf(Str::class, $throwable->message());
        $this->assertSame('foo', (string) $throwable->message());
        $this->assertSame(42, $throwable->code());
        $this->assertInstanceOf(UrlInterface::class, $throwable->file());
        $this->assertSame('file://'.__FILE__, (string) $throwable->file());
        $this->assertInstanceOf(Line::class, $throwable->line());
        $this->assertSame(23, $throwable->line()->toInt());
        $this->assertInstanceOf(StreamInterface::class, $throwable->trace());
        $this->assertSame(Str::class, (string) $throwable->trace()->type());
        $this->assertCount(11, $throwable->trace());
        $this->assertSame($e->getTraceAsString(), (string) $throwable->trace()->join("\n"));
        $this->assertInstanceOf(StreamInterface::class, $throwable->callFrames());
        $this->assertSame(CallFrame::class, (string) $throwable->callFrames()->type());
        $this->assertCount(10, $throwable->callFrames());
    }
}
