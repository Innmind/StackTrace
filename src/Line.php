<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\StackTrace\Exception\DomainException;

/**
 * @psalm-immutable
 */
final class Line
{
    private int $value;

    private function __construct(int $value)
    {
        if ($value < 1) {
            throw new DomainException((string) $value);
        }

        $this->value = $value;
    }

    /**
     * @psalm-pure
     */
    public static function of(int $value): self
    {
        return new self($value);
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function toString(): string
    {
        return (string) $this->value;
    }
}
