<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\FormatPath;

use Innmind\StackTrace\{
    FormatPath,
    Line,
};
use Innmind\Url\Url;
use Innmind\Immutable\Str;

/**
 * @psalm-immutable
 */
final class Truncate implements FormatPath
{
    private Url $workingDirectory;

    private function __construct(Url $workingDirectory)
    {
        $this->workingDirectory = $workingDirectory;
    }

    #[\Override]
    public function __invoke(Url $url, Line $line): string
    {
        $workingDirectory = Str::of($this->workingDirectory->path()->toString());
        /** @psalm-suppress ArgumentTypeCoercion */
        $path = Str::of($url->path()->toString())
            ->drop($workingDirectory->length())
            ->toString();

        return "$path:{$line->toString()}";
    }

    /**
     * @psalm-pure
     */
    #[\NoDiscard]
    public static function of(Url $workingDirectory): self
    {
        return new self($workingDirectory);
    }
}
