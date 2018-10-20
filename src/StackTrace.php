<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Immutable\{
    StreamInterface,
    Stream,
};

final class StackTrace
{
    private $throwable;
    private $previous;

    public function __construct(\Throwable $e)
    {
        $this->throwable = new Throwable($e);
        $this->previous = Stream::of(Throwable::class);

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
     * @return StreamInterface<Throwable>
     */
    public function previous(): StreamInterface
    {
        return $this->previous;
    }
}
