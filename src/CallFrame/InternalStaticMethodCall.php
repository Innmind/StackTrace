<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\CallFrame;
use Innmind\Immutable\{
    SequenceInterface,
    Sequence,
};

/**
 * Function called within language function (ie: array_map) or by reflection
 */
final class InternalStaticMethodCall implements CallFrame
{
    private $class;
    private $method;
    private $arguments;

    public function __construct(
        ClassName $class,
        Method $method,
        ...$arguments
    ) {
        $this->class = $class;
        $this->method = $method;
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

    public function arguments(): SequenceInterface
    {
        return $this->arguments;
    }

    public function __toString(): string
    {
        return "{$this->class}::{$this->method}()";
    }
}
