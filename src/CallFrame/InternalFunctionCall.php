<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame,
    FunctionName,
};
use Innmind\Immutable\{
    SequenceInterface,
    Sequence,
};

/**
 * Function called within language function (ie: array_map) or by reflection
 */
final class InternalFunctionCall implements CallFrame
{
    private $functionName;
    private $arguments;

    public function __construct(FunctionName $functionName, ...$arguments)
    {
        $this->functionName = $functionName;
        $this->arguments = Sequence::of(...$arguments);
    }

    public function functionName(): FunctionName
    {
        return $this->functionName;
    }

    public function arguments(): SequenceInterface
    {
        return $this->arguments;
    }

    public function __toString(): string
    {
        return "{$this->functionName}()";
    }
}
