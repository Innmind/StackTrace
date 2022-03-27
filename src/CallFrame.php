<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
interface CallFrame
{
    /**
     * @return Sequence<mixed>
     */
    public function arguments(): Sequence;
    public function toString(): string;
}
