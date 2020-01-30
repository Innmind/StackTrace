<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\StackTrace\Exception\DomainException;
use Innmind\Immutable\Str;

final class ClassName
{
    private string $value;

    public function __construct(string $value)
    {
        if (Str::of($value)->empty()) {
            throw new DomainException;
        }

        $this->value = $value;
    }

    public function toString(): string
    {
        return $this->value;
    }
}
