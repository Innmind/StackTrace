<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    Line,
    Exception\DomainException,
};
use PHPUnit\Framework\TestCase;
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    Set,
};

class LineTest extends TestCase
{
    use BlackBox;

    public function testInterface()
    {
        $this
            ->forAll(Set\Integers::above(0))
            ->then(function(int $int): void {
                $this->assertSame($int, Line::of($int)->toInt());
                $this->assertSame((string) $int, Line::of($int)->toString());
            });
    }

    public function testThrowWhenZero()
    {
        $this->expectException(DomainException::class);

        Line::of(0);
    }

    public function testThrowWhenNegativeValue()
    {
        $this
            ->forAll(Set\Integers::below(1))
            ->then(function(int $int): void {
                $this->expectException(DomainException::class);

                Line::of($int);
            });
    }
}
