<?php
declare(strict_types = 1);

namespace Tests\Innmind\StackTrace;

use Innmind\StackTrace\{
    Line,
    Exception\DomainException,
};
use Innmind\BlackBox\{
    PHPUnit\BlackBox,
    PHPUnit\Framework\TestCase,
    Set,
};

class LineTest extends TestCase
{
    use BlackBox;

    public function testInterface(): BlackBox\Proof
    {
        return $this
            ->forAll(Set::integers()->above(0))
            ->prove(function(int $int): void {
                $this->assertSame($int, Line::of($int)->toInt());
                $this->assertSame((string) $int, Line::of($int)->toString());
            });
    }

    public function testThrowWhenZero()
    {
        $this->expectException(DomainException::class);

        Line::of(0);
    }

    public function testThrowWhenNegativeValue(): BlackBox\Proof
    {
        return $this
            ->forAll(Set::integers()->below(1))
            ->prove(function(int $int): void {
                $this->expectException(DomainException::class);

                Line::of($int);
            });
    }
}
