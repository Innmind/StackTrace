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

/**
 * @psalm-immutable
 */
final class MethodCall implements UserLand
{
    private ClassName $class;
    private Method $method;
    private Url $file;
    private Line $line;
    private Sequence $arguments;

    /**
     * @no-named-arguments
     */
    private function __construct(
        ClassName $class,
        Method $method,
        Url $file,
        Line $line,
        mixed ...$arguments,
    ) {
        $this->class = $class;
        $this->method = $method;
        $this->file = $file;
        $this->line = $line;
        $this->arguments = Sequence::mixed(...$arguments);
    }

    /**
     * @no-named-arguments
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(
        ClassName $class,
        Method $method,
        Url $file,
        Line $line,
        mixed ...$arguments,
    ): self {
        return new self($class, $method, $file, $line, ...$arguments);
    }

    #[\NoDiscard]
    public function class(): ClassName
    {
        return $this->class;
    }

    #[\NoDiscard]
    public function method(): Method
    {
        return $this->method;
    }

    #[\Override]
    #[\NoDiscard]
    public function file(): Url
    {
        return $this->file;
    }

    #[\Override]
    #[\NoDiscard]
    public function line(): Line
    {
        return $this->line;
    }

    #[\Override]
    #[\NoDiscard]
    public function arguments(): Sequence
    {
        return $this->arguments;
    }

    #[\Override]
    #[\NoDiscard]
    public function toString(): string
    {
        return "{$this->class->toString()}->{$this->method->toString()}()";
    }
}
