<?php

require __DIR__.'/vendor/autoload.php';

use Innmind\OperatingSystem\Factory;
use Innmind\Url\Path;
use Innmind\Server\Control\Server\Command;
use Innmind\Immutable\Either;

$os = Factory::build();
$watch = $os->filesystem()->watch(Path::of(__DIR__.'/graph.dot'));

$watch($os, function($os): Either {
    $process = $os
        ->control()
        ->processes()
        ->execute(
            Command::foreground('dot')
                ->withShortOption('Tsvg')
                ->withShortOption('o', 'graph.svg')
                ->withArgument('graph.dot')
        );

    echo $process->output()->toString();
    echo 'rendered'."\n";

    return Either::right($os);
});
