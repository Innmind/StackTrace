<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\Link;

use Innmind\StackTrace\{
    Link,
    Line,
};
use Innmind\Url\Url;

final class ToFile implements Link
{
    public function __invoke(Url $file, Line $line): Url
    {
        return $file;
    }
}
