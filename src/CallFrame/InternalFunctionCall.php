<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame,
    FunctionName,
};
use Innmind\Immutable\Sequence;

/**
 * Function called within language function (ie: array_map) or by reflection
 * @psalm-immutable
 */
final class InternalFunctionCall implements CallFrame
{
    private FunctionName $functionName;
    private Sequence $arguments;

    /**
     * @no-named-arguments
     */
    private function __construct(FunctionName $functionName, mixed ...$arguments)
    {
        $this->functionName = $functionName;
        $this->arguments = Sequence::mixed(...$arguments);
    }

    /**
     * @no-named-arguments
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(FunctionName $functionName, mixed ...$arguments): self
    {
        return new self($functionName, ...$arguments);
    }

    #[\NoDiscard]
    public function functionName(): FunctionName
    {
        return $this->functionName;
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
        return "{$this->functionName->toString()}()";
    }
}
