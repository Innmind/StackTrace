<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\StackTrace\Exception\DomainException;

final class Line
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 1) {
            throw new DomainException((string) $value);
        }

        $this->value = $value;
    }

    public function toInt(): int
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return (string) $this->value;
    }
}
