<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\{
    CallFrame,
    Line,
};
use Innmind\Url\Url;

/**
 * @psalm-immutable
 */
interface UserLand extends CallFrame
{
    public function file(): Url;
    public function line(): Line;
}
