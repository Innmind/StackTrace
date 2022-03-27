<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\Sequence;

/**
 * @psalm-immutable
 */
final class StackTrace
{
    private Throwable $throwable;
    /** @var Sequence<Throwable> */
    private Sequence $previous;

    private function __construct(\Throwable $e)
    {
        $this->throwable = Throwable::of($e);
        /** @var Sequence<Throwable> */
        $this->previous = Sequence::of();

        while ($previous = $e->getPrevious()) {
            $this->previous = ($this->previous)(Throwable::of($previous));
            $e = $previous;
        }
    }

    /**
     * @psalm-pure
     */
    public static function of(\Throwable $e): self
    {
        return new self($e);
    }

    public function throwable(): Throwable
    {
        return $this->throwable;
    }

    /**
     * @return Sequence<Throwable>
     */
    public function previous(): Sequence
    {
        return $this->previous;
    }
}
