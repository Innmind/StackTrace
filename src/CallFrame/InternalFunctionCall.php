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
 */
final class InternalFunctionCall implements CallFrame
{
    private FunctionName $functionName;
    private Sequence $arguments;

    /**
     * @param mixed $arguments
     */
    public function __construct(FunctionName $functionName, ...$arguments)
    {
        $this->functionName = $functionName;
        $this->arguments = Sequence::mixed(...$arguments);
    }

    public function functionName(): FunctionName
    {
        return $this->functionName;
    }

    public function arguments(): Sequence
    {
        return $this->arguments;
    }

    public function __toString(): string
    {
        return "{$this->functionName}()";
    }
}
