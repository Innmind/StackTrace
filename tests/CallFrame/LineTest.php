<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame\Line,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Eris\{
    Generator,
    TestTrait,
};

class LineTest extends TestCase
{
    use TestTrait;

    public function testInterface()
    {
        $this
            ->forAll(Generator\pos())
            ->then(function(int $int): void {
                $this->assertSame($int, (new Line($int))->toInt());
                $this->assertSame((string) $int, (string) new Line($int));
            });
    }

    public function testThrowWhenZero()
    {
        $this->expectException(DomainException::class);

        new Line(0);
    }

    public function testThrowWhenNegativeValue()
    {
        $this
            ->forAll(Generator\neg())
            ->then(function(int $int): void {
                $this->expectException(DomainException::class);

                new Line($int);
            });
    }
}
