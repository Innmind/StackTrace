<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\Url;

interface Link
{
    public function __invoke(Url $file, Line $line): Url;
}
