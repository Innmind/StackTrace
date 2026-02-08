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
    #[\NoDiscard]
    public function arguments(): Sequence;

    #[\NoDiscard]
    public function toString(): string;
}
