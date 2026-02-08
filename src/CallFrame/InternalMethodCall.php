<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame,
    ClassName,
    Method,
};
use Innmind\Immutable\Sequence;

/**
 * Function called within language function (ie: array_map) or by reflection
 * @psalm-immutable
 */
final class InternalMethodCall implements CallFrame
{
    private ClassName $class;
    private Method $method;
    private Sequence $arguments;

    /**
     * @no-named-arguments
     */
    private function __construct(
        ClassName $class,
        Method $method,
        mixed ...$arguments,
    ) {
        $this->class = $class;
        $this->method = $method;
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
        mixed ...$arguments,
    ): self {
        return new self($class, $method, ...$arguments);
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
