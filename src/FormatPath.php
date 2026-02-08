<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\Url;

/**
 * @psalm-immutable
 */
interface FormatPath
{
    /**
     * @return non-empty-string
     */
    #[\NoDiscard]
    public function __invoke(Url $url, Line $line): string;
}
