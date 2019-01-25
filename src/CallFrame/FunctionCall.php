<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    Line,
    FunctionName,
};
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    SequenceInterface,
    Sequence,
};

final class FunctionCall implements UserLand
{
    private $functionName;
    private $file;
    private $line;
    private $arguments;

    public function __construct(
        FunctionName $functionName,
        UrlInterface $file,
        Line $line,
        ...$arguments
    ) {
        $this->functionName = $functionName;
        $this->file = $file;
        $this->line = $line;
        $this->arguments = Sequence::of(...$arguments);
    }

    public function functionName(): FunctionName
    {
        return $this->functionName;
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
        return "$this->functionName()";
    }
}
