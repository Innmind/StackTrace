<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\Link;

use Innmind\StackTrace\{
    Link,
    Line,
};
use Innmind\Url\UrlInterface;

final class ToFile implements Link
{
    public function __invoke(UrlInterface $file, Line $line): UrlInterface
    {
        return $file;
    }
}
