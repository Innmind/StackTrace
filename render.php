<?php

require __DIR__.'/vendor/autoload.php';

use function Innmind\FileWatch\bootstrap;
use Innmind\OperatingSystem\Factory;
use Innmind\TimeWarp\Halt\Usleep;
use Innmind\Url\Path;
use Innmind\Server\Control\Server\Command;

$os = Factory::build();
$watch = bootstrap($os->control()->processes(), new Usleep, $os->clock());

$watch(Path::of(__DIR__.'/graph.dot'))(function() use ($os): void {
    $process = $os
        ->control()
        ->processes()
        ->execute(
            Command::foreground('dot')
                ->withShortOption('Tsvg')
                ->withShortOption('o', 'graph.svg')
                ->withArgument('graph.dot')
        );
    $process->wait();

    echo $process->output()->toString();
    echo 'rendered'."\n";
});
