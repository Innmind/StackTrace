<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\{
    UrlInterface,
    Url,
};
use Innmind\Immutable\{
    StreamInterface,
    Str,
};

final class Throwable
{
    private $class;
    private $code;
    private $message;
    private $file;
    private $line;
    private $trace;
    private $frames;

    public function __construct(\Throwable $e)
    {
        $this->class = new ClassName(\get_class($e));
        $this->code = $e->getCode();
        $this->message = Str::of($e->getMessage());
        $this->file = Url::fromString('file://'.$e->getFile());
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

    public function file(): UrlInterface
    {
        return $this->file;
    }

    public function line(): Line
    {
        return $this->line;
    }

    /**
     * @return StreamInterface<Str>
     */
    public function trace(): StreamInterface
    {
        return $this->trace;
    }

    /**
     * @return StreamInterface<CallFrame>
     */
    public function callFrames(): StreamInterface
    {
        return $this->frames;
    }
}
