<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    ClassName,
    Method,
    Line,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

final class MethodCall implements UserLand
{
    private ClassName $class;
    private Method $method;
    private Url $file;
    private Line $line;
    private Sequence $arguments;

    /**
     * @no-named-arguments
     * @param mixed $arguments
     */
    public function __construct(
        ClassName $class,
        Method $method,
        Url $file,
        Line $line,
        ...$arguments
    ) {
        $this->class = $class;
        $this->method = $method;
        $this->file = $file;
        $this->line = $line;
        $this->arguments = Sequence::mixed(...$arguments);
    }

    public function class(): ClassName
    {
        return $this->class;
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function file(): Url
    {
        return $this->file;
    }

    public function line(): Line
    {
        return $this->line;
    }

    public function arguments(): Sequence
    {
        return $this->arguments;
    }

    public function toString(): string
    {
        return "{$this->class->toString()}->{$this->method->toString()}()";
    }
}
