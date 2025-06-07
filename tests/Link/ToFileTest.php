<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\Link;

use Innmind\StackTrace\{
    Link\ToFile,
    Link,
    Line,
};
use Innmind\Url\Url;
use Innmind\BlackBox\PHPUnit\Framework\TestCase;

class ToFileTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Link::class, new ToFile);
    }

    public function testInvokation()
    {
        $link = new ToFile;
        $file = Url::of('http://example.com');

        $this->assertSame($file, $link($file, Line::of(42)));
    }
}
