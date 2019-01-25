<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    Render,
    StackTrace,
    Exception\DomainException,
};
use Innmind\Stream\Readable;
use PHPUnit\Framework\TestCase;

class RenderTest extends TestCase
{
    public function testInvokation()
    {
        $render = new Render;

        try {
            $refl = new \ReflectionMethod(CallFramesTest::class, 'refl');
            $refl->invoke($this);
        } catch (\Throwable $e) {
            // pass
        }

        $graph = $render(new StackTrace(new DomainException('', 0, $e)));

        $this->assertInstanceOf(Readable::class, $graph);
        $this->assertNotEmpty((string) $graph);
        file_put_contents('graph.dot', (string) $graph);
    }
}
