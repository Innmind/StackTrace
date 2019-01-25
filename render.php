<?php

require __DIR__.'/vendor/autoload.php';

use function Innmind\FileWatch\bootstrap;
use Innmind\OperatingSystem\Factory;
use Innmind\TimeWarp\Halt\Usleep;
use Innmind\Url\Path;
use Innmind\Server\Control\Server\Command;

$os = Factory::build();
$watch = bootstrap($os, new Usleep);

$watch(new Path(__DIR__.'/graph.dot'))(function() use ($os): void {
    $output = $os
        ->control()
        ->processes()
        ->execute(
            Command::foreground('dot')
                ->withShortOption('Tsvg')
                ->withShortOption('o', 'graph.svg')
                ->withArgument('graph.dot')
        )
        ->wait()
        ->output();
    echo $output;
    echo 'rendered'."\n";
});
