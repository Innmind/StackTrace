<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    ClassName,
    Method,
    Line,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    SequenceInterface,
    Sequence,
};

final class StaticMethodCall implements UserLand
{
    private ClassName $class;
    private Method $method;
    private UrlInterface $file;
    private Line $line;
    private Sequence $arguments;

    public function __construct(
        ClassName $class,
        Method $method,
        UrlInterface $file,
        Line $line,
        ...$arguments
    ) {
        $this->class = $class;
        $this->method = $method;
        $this->file = $file;
        $this->line = $line;
        $this->arguments = Sequence::of(...$arguments);
    }

    public function class(): ClassName
    {
        return $this->class;
    }

    public function method(): Method
    {
        return $this->method;
    }

    public function file(): UrlInterface
    {
        return $this->file;
    }

    public function line(): Line
    {
        return $this->line;
    }

    public function arguments(): SequenceInterface
    {
        return $this->arguments;
    }

    public function __toString(): string
    {
        return "{$this->class}::{$this->method}()";
    }
}
