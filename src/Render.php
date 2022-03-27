<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Graphviz\{
    Graph,
    Edge,
    Node,
    Node\Shape,
    Layout\Dot,
};
use Innmind\Filesystem\File\Content;
use Innmind\Colour\Colour;
use Innmind\Immutable\{
    Map,
    Str,
    Maybe,
    Sequence,
};

/**
 * @psalm-immutable
 */
final class Render
{
    private Link $link;

    private function __construct(Link $link = null)
    {
        $this->link = $link ?? new Link\ToFile;
    }

    public function __invoke(StackTrace $stack): Content
    {
        $thrown = $this->thrown(
            Graph::directed('throwables')->displayAs('Thrown'),
            $stack,
        );
        $thrown = $this->linkCausality($thrown, $stack);
        $callFrames = $this->callFrames(
            $this->link,
            Graph::directed('call_frames')->displayAs('Stack Trace'),
            $stack,
        );
        $graph = $this->linkSources(
            $this->link,
            Graph::directed('stack_trace'),
            $stack,
        );

        return Dot::of()(
            $graph
                ->cluster($thrown)
                ->cluster($callFrames),
        );
    }

    /**
     * @psalm-pure
     */
    public static function of(Link $link = null): self
    {
        return new self($link);
    }

    /**
     * @param Graph<'directed'> $thrown
     *
     * @return Graph<'directed'>
     */
    private function thrown(Graph $thrown, StackTrace $stack): Graph
    {
        return $stack
            ->previous()
            ->add($stack->throwable())
            ->reduce($thrown, $this->throwable(...));
    }

    /**
     * @param Graph<'directed'> $thrown
     *
     * @return Graph<'directed'>
     */
    private function throwable(Graph $thrown, Throwable $e): Graph
    {
        return $thrown->add(
            $this
                ->node($e)
                ->displayAs(\sprintf(
                    '%s[%s](%s)',
                    $this->clean($e->class()->toString()),
                    $e->code(),
                    $e->message()->toString(),
                ))
                ->shaped(Shape::doubleoctagon()->fillWithColor(Colour::red->toRGBA()))
                ->target(($this->link)($e->file(), $e->line())),
        );
    }

    /**
     * @param Graph<'directed'> $thrown
     *
     * @return Graph<'directed'>
     */
    private function linkCausality(Graph $thrown, StackTrace $stack): Graph
    {
        $remaining = Sequence::of($stack->throwable())->append($stack->previous());

        do {
            [$top, $remaining] = $remaining->match(
                static fn($first, $remaining) => [Maybe::just($first), $remaining],
                static fn() => [Maybe::nothing(), Sequence::of()],
            );

            $thrown = Maybe::all($top, $remaining->first())
                ->map(fn(Throwable $top, Throwable $bottom) => $thrown->add(
                    $this->node($top)->linkedTo(
                        $this->nodeName($bottom),
                        static fn($edge) => $edge->displayAs('Caused by'),
                    ),
                ))
                ->match(
                    static fn($thrown) => $thrown,
                    static fn() => $thrown,
                );
        } while (!$remaining->empty());

        return $thrown;
    }

    /**
     * @param Graph<'directed'> $callFrames
     *
     * @return Graph<'directed'>
     */
    private function callFrames(
        Link $link,
        Graph $callFrames,
        StackTrace $stack,
    ): Graph {
        $frames = $stack
            ->previous()
            ->add($stack->throwable())
            ->map(static fn($e) => $e->callFrames())
            ->flatMap(static fn($frames) => $frames)
            ->map(fn($frame) => [
                $this->hashFrame($frame),
                $frame,
            ])
            ->toList();

        $frames = Map::of(...$frames)->values();
        $callFrames = $frames->reduce($callFrames, $this->callFrame(...));

        $deepest = $stack
            ->previous()
            ->last()
            ->match(
                static fn($deepest) => $deepest,
                static fn() => $stack->throwable(),
            );

        return $this->linkCallFrames($link, $callFrames, $deepest);
    }

    /**
     * @param Graph<'directed'> $callFrames
     *
     * @return Graph<'directed'>
     */
    private function callFrame(Graph $callFrames, CallFrame $frame): Graph
    {
        $node = Node::of($this->nodeName($frame))
            ->displayAs($this->clean($frame->toString()))
            ->shaped(Shape::box()->fillWithColor(Colour::orange->toRGBA()));

        if ($frame instanceof CallFrame\UserLand) {
            $node = $node->target(($this->link)($frame->file(), $frame->line()));
        }

        return $callFrames->add($node);
    }

    /**
     * @param Graph<'directed'> $callFrames
     *
     * @return Graph<'directed'>
     */
    private function linkCallFrames(
        Link $link,
        Graph $callFrames,
        Throwable $e,
    ): Graph {
        $remaining = $e->callFrames();

        while (!$remaining->empty()) {
            [$first, $remaining] = $remaining->match(
                static fn($first, $remaining) => [Maybe::just($first), $remaining],
                static fn() => [Maybe::nothing(), Sequence::of()],
            );

            $callFrames = Maybe::all($remaining->first(), $first)
                ->map(fn(CallFrame $top, CallFrame $bottom) => $callFrames->add(
                    $this->node($top)->linkedTo(
                        $this->nodeName($bottom),
                        static fn($edge) => self::configureEdge($link, $edge, $top),
                    ),
                ))
                ->match(
                    static fn($callFrames) => $callFrames,
                    static fn() => $callFrames,
                );
        }

        return $callFrames;
    }

    /**
     * @param Graph<'directed'> $graph
     *
     * @return Graph<'directed'>
     */
    private function linkSources(Link $link, Graph $graph, StackTrace $stack): Graph
    {
        /**
         * @psalm-suppress InvalidArgument
         * @var callable(Graph<'directed'>, Throwable): Graph<'directed'>
         */
        $reducer = fn(Graph $graph, Throwable $e): Graph => $this->linkSource(
            $link,
            $graph,
            $e,
        );

        return $stack
            ->previous()
            ->add($stack->throwable())
            ->reduce(
                $graph,
                $reducer,
            );
    }

    /**
     * @param Graph<'directed'> $graph
     *
     * @return Graph<'directed'>
     */
    private function linkSource(Link $link, Graph $graph, Throwable $e): Graph
    {
        return $e
            ->callFrames()
            ->first()
            ->match(
                fn($frame) => $graph->add(
                    $this->node($e)->linkedTo(
                        $this->nodeName($frame),
                        static fn($edge) => $edge
                            ->displayAs("{$e->file()->path()->toString()}:{$e->line()->toString()}")
                            ->target($link($e->file(), $e->line())),
                    ),
                ),
                static fn() => $graph,
            );
    }

    /**
     * Remove special characters and escape backslashes
     */
    private function clean(string $name): string
    {
        return Str::of($name)
            ->replace("\x00", '') // remove the invisible character used in the name of anonymous classes
            ->replace('\\', '\\\\')
            ->toString();
    }

    private function hashThrowable(Throwable $e): string
    {
        return \spl_object_hash($e);
    }

    private function hashFrame(CallFrame $frame): string
    {
        $prefix = '';

        if ($frame instanceof CallFrame\UserLand) {
            $prefix = "{$frame->file()->path()->toString()}|{$frame->line()->toString()}|";
        }

        return "$prefix{$frame->toString()}|{$frame->arguments()->count()}";
    }

    private function node(CallFrame|Throwable $reference): Node
    {
        return Node::of($this->nodeName($reference));
    }

    private function nodeName(CallFrame|Throwable $reference): Node\Name
    {
        if ($reference instanceof Throwable) {
            return Node\Name::of('exception_'.$this->hashThrowable($reference));
        }

        return Node\Name::of('call_frame_'.\md5($this->hashFrame($reference)));
    }

    /**
     * @psalm-pure
     */
    private static function configureEdge(Link $link, Edge $edge, CallFrame $frame): Edge
    {
        if (!$frame instanceof CallFrame\UserLand) {
            return $edge;
        }

        return $edge
            ->displayAs("{$frame->file()->path()->toString()}:{$frame->line()->toString()}")
            ->target($link($frame->file(), $frame->line()));
    }
}
