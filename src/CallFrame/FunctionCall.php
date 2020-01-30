<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    Line,
    FunctionName,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

final class FunctionCall implements UserLand
{
    private FunctionName $functionName;
    private Url $file;
    private Line $line;
    private Sequence $arguments;

    /**
     * @param mixed $arguments
     */
    public function __construct(
        FunctionName $functionName,
        Url $file,
        Line $line,
        ...$arguments
    ) {
        $this->functionName = $functionName;
        $this->file = $file;
        $this->line = $line;
        $this->arguments = Sequence::mixed(...$arguments);
    }

    public function functionName(): FunctionName
    {
        return $this->functionName;
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
        return "{$this->functionName->toString()}()";
    }
}
