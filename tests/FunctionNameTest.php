<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    FunctionName,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class FunctionNameTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set\Strings::any()->filter(static fn($string) => $string !== ''))
            ->then(function(string $string): void {
                $this->assertSame($string, FunctionName::of($string)->toString());
            });
    }

    public function testThrowWhenEmptyValue()
    {
        $this->expectException(DomainException::class);

        FunctionName::of('');
    }
}
