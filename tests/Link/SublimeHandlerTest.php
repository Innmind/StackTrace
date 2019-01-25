<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\Link;

use Innmind\StackTrace\{
    Link\SublimeHandler,
    Link,
    Line,
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class SublimeHandlerTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Link::class, new SublimeHandler);
    }

    public function testInvokation()
    {
        $link = new SublimeHandler;
        $file = Url::fromString('file:///foo/bar/baz.php');

        $this->assertSame(
            'sublime:///foo/bar/baz.php',
            (string) $link($file, new Line(42))
        );
    }
}
