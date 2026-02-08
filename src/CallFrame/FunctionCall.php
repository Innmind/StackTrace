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
    #[\NoDiscard]
    public static function of(
        FunctionName $functionName,
        Url $file,
        Line $line,
        mixed ...$arguments,
    ): self {
        return new self($functionName, $file, $line, ...$arguments);
    }

    #[\NoDiscard]
    public function functionName(): FunctionName
    {
        return $this->functionName;
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
        return "{$this->functionName->toString()}()";
    }
}
