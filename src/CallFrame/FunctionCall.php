<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    Line,
    FunctionName,
};
use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
final class FunctionCall implements UserLand
{
    private FunctionName $functionName;
    private Url $file;
    private Line $line;
    private Sequence $arguments;

    /**
     * @no-named-arguments
     */
    private function __construct(
        FunctionName $functionName,
        Url $file,
        Line $line,
        mixed ...$arguments,
    ) {
        $this->functionName = $functionName;
        $this->file = $file;
        $this->line = $line;
        $this->arguments = Sequence::mixed(...$arguments);
    }

    /**
     * @no-named-arguments
     * @psalm-pure
     */
    public static function of(
        FunctionName $functionName,
        Url $file,
        Line $line,
        mixed ...$arguments,
    ): self {
        return new self($functionName, $file, $line, ...$arguments);
    }

    public function functionName(): FunctionName
    {
        return $this->functionName;
    }

    #[\Override]
    public function file(): Url
    {
        return $this->file;
    }

    #[\Override]
    public function line(): Line
    {
        return $this->line;
    }

    #[\Override]
    public function arguments(): Sequence
    {
        return $this->arguments;
    }

    #[\Override]
    public function toString(): string
    {
        return "{$this->functionName->toString()}()";
    }
}
