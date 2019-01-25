<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\Link;

use Innmind\StackTrace\{
    Link\ToFile,
    Link,
    Line,
};
use Innmind\Url\UrlInterface;
use PHPUnit\Framework\TestCase;

class ToFileTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Link::class, new ToFile);
    }

    public function testInvokation()
    {
        $link = new ToFile;
        $file = $this->createMock(UrlInterface::class);

        $this->assertSame($file, $link($file, new Line(42)));
    }
}
