<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\Url;
use Innmind\Immutable\{
    Sequence,
    Str,
};

final class Throwable
{
    private ClassName $class;
    private int $code;
    private Str $message;
    private Url $file;
    private Line $line;
    private Sequence $trace;
    private Sequence $frames;

    public function __construct(\Throwable $e)
    {
        $this->class = new ClassName(\get_class($e));
        $this->code = $e->getCode();
        $this->message = Str::of($e->getMessage());
        $this->file = Url::of('file://'.$e->getFile());
        $this->line = new Line($e->getLine());
        $this->trace = Str::of($e->getTraceAsString())->split("\n");
        $this->frames = CallFrames::of($e);
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
