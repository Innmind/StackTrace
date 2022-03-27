<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\FormatPath;

use Innmind\StackTrace\{
    FormatPath,
    Line,
};
use Innmind\Url\Url;

/**
 * @psalm-immutable
 */
final class FullPath implements FormatPath
{
    public function __invoke(Url $url, Line $line): string
    {
        return "{$url->path()->toString()}:{$line->toString()}";
    }
}
