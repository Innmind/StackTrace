<?php
declare(strict_types = 1);

namespace Innmind\StackTrace\CallFrame;

use Innmind\StackTrace\CallFrame;
use Innmind\Url\UrlInterface;

interface UserLand extends CallFrame
{
    public function file(): UrlInterface;
    public function line(): Line;
}
