<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\ClassName,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class ClassNameTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\string())
            ->when(static function(string $string): bool {
                return $string !== '';
            })
            ->then(function(string $string): void {
                $this->assertSame($string, (string) new ClassName($string));
            });
    }

    public function testThrowWhenEmptyValue()
    {
        $this->expectException(DomainException::class);

        new ClassName('');
    }
}
