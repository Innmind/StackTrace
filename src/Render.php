<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Graphviz\{
    Graph,
    Node,
    Node\Shape,
    Layout\Dot,
};
use Innmind\Stream\Readable;
use Innmind\Colour\Colour;
use Innmind\Url\UrlInterface;
use Innmind\Immutable\{
    MapInterface,
    Map,
    Str,
};

final class Render
{
    private $nodes;

    public function __invoke(StackTrace $stack): Readable
    {
        try {
            $this->nodes = Map::of('string', Node::class);

            $this->renderNodes($stack);
            $this->renderLinks($stack);

            $graph = $this->nodes->values()->reduce(
                Graph\Graph::directed('stack_trace'),
                static function(Graph $graph, Node $node): Graph {
                    return $graph->add($node);
                }
            );

            return (new Dot)($graph);
        } finally {
            $this->nodes = null;
        }
    }

    private function renderNodes(StackTrace $stack): void
    {
        $stack
            ->previous()
            ->add($stack->throwable())
            ->foreach(function(Throwable $e): void {
                $this->renderThrowable($e);

                $e
                    ->callFrames()
                    ->foreach(function(CallFrame $frame): void {
                        $this->renderCallFrame($frame);
                    });
            });
    }

    private function renderThrowable(Throwable $e): void
    {
        $node = Node\Node::named('exception_'.$this->hashThrowable($e))
            ->displayAs(\sprintf(
                '%s[%s](%s)',
                $this->clean((string) $e->class()),
                $e->code(),
                $e->message()
            ))
            ->shaped(Shape::doubleoctagon()->fillWithColor(Colour::fromString('red')));

        $this->add(
            $this->hashThrowable($e),
            $node
        );
    }

    private function renderCallFrame(CallFrame $frame): void
    {
        $hash = $this->hashFrame($frame);

        if ($this->nodes->contains($hash)) {
            return;
        }

        $name = $this->clean((string) $frame);

        $node = Node\Node::named('call_frame_'.\md5($hash))
            ->displayAs($name)
            ->shaped(Shape::box()->fillWithColor(Colour::fromString('orange')));

        if ($frame instanceof CallFrame\UserLand) {
            $node->target($this->link($frame));
        }

        $this->add($hash, $node);
    }

    private function renderLinks(StackTrace $stack): void
    {
        $stack
            ->previous()
            ->reduce(
                $stack->throwable(),
                function(Throwable $e, Throwable $previous): Throwable {
                    $this->linkCausality($previous, $e);

                    return $previous;
                }
            );

        $stack
            ->previous()
            ->add($stack->throwable())
            ->foreach(function(Throwable $e): void {
                $this->linkCallFrames($e);
            });
    }

    private function linkCausality(Throwable $cause, Throwable $consequence): void
    {
        $consequence = $this->nodes->get($this->hashThrowable($consequence));
        $cause = $this->nodes->get($this->hashThrowable($cause));

        $consequence
            ->linkedTo($cause)
            ->displayAs('Caused By');
    }

    private function linkCallFrames(Throwable $e): void
    {
        if ($e->callFrames()->empty()) {
            return;
        }

        $source = $e->callFrames()->first();
        $this
            ->nodes
            ->get($this->hashThrowable($e))
            ->linkedTo(
                $this->nodes->get($this->hashFrame($source))
            )
            ->displayAs('Thrown in');


        $e
            ->callFrames()
            ->drop(1)
            ->reduce(
                $source,
                function(CallFrame $frame, CallFrame $parent): CallFrame {
                    $frameNode = $this->nodes->get($this->hashFrame($frame));
                    $parentNode = $this->nodes->get($this->hashFrame($parent));

                    if (!$parentNode->edges()->empty()) {
                        // don't add a link if one already present as it would
                        // happen in the case of an exception triggered another one
                        return $parent;
                    }

                    $edge = $parentNode->linkedTo($frameNode);

                    if ($parent instanceof CallFrame\UserLand) {
                        $edge
                            ->displayAs("{$parent->file()->path()}:{$parent->line()}")
                            ->target($this->link($parent));
                    }

                    return $parent;
                }
            );
    }

    private function add(string $reference, Node $node): void
    {
        $this->nodes = $this->nodes->put($reference, $node);
    }

    /**
     * Remove special characters and escape backslashes
     */
    private function clean(string $name): string
    {
        return (string) Str::of($name)
            ->replace("\x00", '') // remove the invisible character used in the name of anonymous classes
            ->replace('\\', '\\\\');
    }

    private function hashThrowable(Throwable $e): string
    {
        return \spl_object_hash($e);
    }

    private function hashFrame(CallFrame $frame): string
    {
        $prefix = '';

        if ($frame instanceof CallFrame\UserLand) {
            $prefix = "{$frame->file()->path()}|{$frame->line()}|";
        }

        return "$prefix$frame|{$frame->arguments()->count()}";
    }

    private function link(CallFrame $frame): UrlInterface
    {
        return $frame->file();
    }
}
