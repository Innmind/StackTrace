<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\{
    StreamInterface,
    Str,
};

final class Throwable
{
    private $class;
    private $code;
    private $message;
    private $trace;
    private $frames;

    public function __construct(\Throwable $e)
    {
        $this->class = Str::of(\get_class($e));
        $this->code = $e->getCode();
        $this->message = Str::of($e->getMessage());
        $this->trace = Str::of($e->getTraceAsString())->split("\n");
        $this->frames = CallFrames::of($e);
    }

    public function class(): Str
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
