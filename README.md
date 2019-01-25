# StackTrace

| `master` | `develop` |
|----------|-----------|
| [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/StackTrace/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Innmind/StackTrace/?branch=master) | [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Innmind/StackTrace/badges/quality-score.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/StackTrace/?branch=develop) |
| [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/StackTrace/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Innmind/StackTrace/?branch=master) | [![Code Coverage](https://scrutinizer-ci.com/g/Innmind/StackTrace/badges/coverage.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/StackTrace/?branch=develop) |
| [![Build Status](https://scrutinizer-ci.com/g/Innmind/StackTrace/badges/build.png?b=master)](https://scrutinizer-ci.com/g/Innmind/StackTrace/build-status/master) | [![Build Status](https://scrutinizer-ci.com/g/Innmind/StackTrace/badges/build.png?b=develop)](https://scrutinizer-ci.com/g/Innmind/StackTrace/build-status/develop) |

Tool to inspect an Exception stack trace

## Installation

```sh
composer require innmind/stack-trace
```

## Usage

```php
use Innmind\StackTrace\{
    StackTrace,
    Render,
};
use Innmind\OperatingSystem\Factory;
use Innmind\Server\Control\Server\Command;

$trace = new StackTrace(new AnyClassImplementingPhpThrowableInterface);

// every call frames that lead to the exception to be thrown
// (deepest call frame first)
$callFrames = $trace->throwable()->callFrames();

// this will render the stack trace via graphviz
Factory::build()
    ->control()
    ->processes()
    ->execute(
        Command::foreground('dot')
            ->withShortOption('Tsvg')
            ->withShortOption('o', 'graph.svg')
            ->withInput(
                (new Render)($trace)
            )
    )
    ->wait();
```

**Note**: the svg rendered contains links to the files where call frames and exceptions occured, you can change the link by providing an instance of [`Link`](src/Link.php) to the `Render` object.

Example of a rendered stack trace: ![](graph.svg)
