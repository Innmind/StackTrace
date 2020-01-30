<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\Sequence;

final class StackTrace
{
    private Throwable $throwable;
    /** @var Sequence<Throwable> */
    private Sequence $previous;

    public function __construct(\Throwable $e)
    {
        $this->throwable = new Throwable($e);
        /** @var Sequence<Throwable> */
        $this->previous = Sequence::of(Throwable::class);

        while ($previous = $e->getPrevious()) {
            $this->previous = $this->previous->add(new Throwable($previous));
            $e = $previous;
        }
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
