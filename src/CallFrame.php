<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\Sequence;

interface CallFrame
{
    public function arguments(): Sequence;
    public function toString(): string;
}
