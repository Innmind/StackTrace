<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\Url;
use Innmind\Immutable\{
    Sequence,
    Str,
};

/**
 * @psalm-immutable
 */
final class Throwable
{
    private ClassName $class;
    private int $code;
    private Str $message;
    private Url $file;
    private Line $line;
    /** @var Sequence<Str> */
    private Sequence $trace;
    /** @var Sequence<CallFrame> */
    private Sequence $frames;

    private function __construct(\Throwable $e)
    {
        $this->class = ClassName::of(\get_class($e));
        $this->code = (int) $e->getCode();
        $this->message = Str::of($e->getMessage());
        $this->file = Url::of('file://'.$e->getFile());
        $this->line = Line::of($e->getLine());
        $this->trace = Str::of($e->getTraceAsString())->split("\n");
        $this->frames = CallFrames::of($e);
    }

    /**
     * @psalm-pure
     */
    public static function of(\Throwable $e): self
    {
        return new self($e);
    }

    public function class(): ClassName
    {
        return $this->class;
    }

    public function code(): int
    {
        return $this->code;
    }

    public function message(): Str
    {
        return $this->message;
    }

    public function file(): Url
    {
        return $this->file;
    }

    public function line(): Line
    {
        return $this->line;
    }

    /**
     * @return Sequence<Str>
     */
    public function trace(): Sequence
    {
        return $this->trace;
    }

    /**
     * @return Sequence<CallFrame>
     */
    public function callFrames(): Sequence
    {
        return $this->frames;
    }
}
