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
use Innmind\StackTrace\StackTrace;

$trace = new StackTrace(new AnyClassImplementingPhpThrowableInterface);
```
