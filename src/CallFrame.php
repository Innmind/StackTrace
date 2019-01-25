<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\SequenceInterface;

interface CallFrame
{
    public function arguments(): SequenceInterface;
    public function __toString(): string;
}
