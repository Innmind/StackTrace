<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\Link;

use Innmind\StackTrace\{
    Link,
    Line,
};
use Innmind\Url\Url;

/**
 * @psalm-immutable
 */
final class ToFile implements Link
{
    #[\Override]
    public function __invoke(Url $file, Line $line): Url
    {
        return $file;
    }
}
