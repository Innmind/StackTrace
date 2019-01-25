<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\UrlInterface;

interface Link
{
    public function __invoke(UrlInterface $file, Line $line): UrlInterface;
}
