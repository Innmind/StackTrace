<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    Render,
    StackTrace,
    FormatPath\Truncate,
    Exception\DomainException,
};
use Innmind\Filesystem\File\Content;
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class RenderTest extends TestCase
{
    public function testInvokation()
    {
        $render = Render::of(null, Truncate::of(Url::of(\getcwd().'/')));

        try {
            $refl = new \ReflectionMethod(CallFramesTest::class, 'refl');
            $refl->invoke($this);
        } catch (\Throwable $e) {
            // pass
        }

        $graph = $render(StackTrace::of(new DomainException('', 0, $e)));

        $this->assertInstanceOf(Content::class, $graph);
        $this->assertNotEmpty($graph->toString());
        \file_put_contents('graph.dot', $graph->toString());
    }
}
