<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    Throwable,
    CallFrame,
    ClassName,
    Line,
};
use Innmind\Url\Url;
use Innmind\Immutable\{
    Sequence,
    Str,
};
use PHPUnit\Framework\TestCase;

class ThrowableTest extends TestCase
{
    public function testInterface()
    {
        $throwable = Throwable::of($e = new \Exception('foo', 42));

        $this->assertInstanceOf(ClassName::class, $throwable->class());
        $this->assertSame(\Exception::class, $throwable->class()->toString());
        $this->assertInstanceOf(Str::class, $throwable->message());
        $this->assertSame('foo', $throwable->message()->toString());
        $this->assertSame(42, $throwable->code());
        $this->assertInstanceOf(Url::class, $throwable->file());
        $this->assertSame('file://'.__FILE__, $throwable->file()->toString());
        $this->assertInstanceOf(Line::class, $throwable->line());
        $this->assertSame(23, $throwable->line()->toInt());
        $this->assertInstanceOf(Sequence::class, $throwable->trace());
        $this->assertCount(12, $throwable->trace());
        $this->assertSame(
            $e->getTraceAsString(),
            Str::of("\n")->join($throwable->trace()->map(
                static fn($line) => $line->toString(),
            ))->toString(),
        );
        $this->assertInstanceOf(Sequence::class, $throwable->callFrames());
        $this->assertCount(11, $throwable->callFrames());
    }
}
