<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

final class CallFrames
{
    /**
     * @return Sequence<CallFrame>
     */
    public static function of(\Throwable $throwable): Sequence
    {
        $frames = [];

        foreach ($throwable->getTrace() as $frame) {
            $frames[] = self::methodCall($frame) ??
                self::staticMethodCall($frame) ??
                self::internalMethodCall($frame) ??
                self::internalStaticMethodCall($frame) ??
                self::functionCall($frame) ??
                self::internalFunctionCall($frame);
        }

        return Sequence::of(CallFrame::class, ...$frames);
    }

    private static function methodCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('class', $frame)) {
            return null;
        }

        if ($frame['type'] !== '->') {
            return null;
        }

        if (!\array_key_exists('file', $frame)) {
            return null;
        }

        return new CallFrame\MethodCall(
            new ClassName($frame['class']),
            new Method($frame['function']),
            Url::of('file://'.$frame['file']),
            new Line($frame['line']),
            ...$frame['args']
        );
    }

    private static function staticMethodCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('class', $frame)) {
            return null;
        }

        if ($frame['type'] !== '::') {
            return null;
        }

        if (!\array_key_exists('file', $frame)) {
            return null;
        }

        return new CallFrame\StaticMethodCall(
            new ClassName($frame['class']),
            new Method($frame['function']),
            Url::of('file://'.$frame['file']),
            new Line($frame['line']),
            ...$frame['args']
        );
    }

    private static function internalMethodCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('class', $frame)) {
            return null;
        }

        if ($frame['type'] !== '->') {
            return null;
        }

        return new CallFrame\InternalMethodCall(
            new ClassName($frame['class']),
            new Method($frame['function']),
            ...$frame['args']
        );
    }

    private static function internalStaticMethodCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('class', $frame)) {
            return null;
        }

        if ($frame['type'] !== '::') {
            return null;
        }

        return new CallFrame\InternalStaticMethodCall(
            new ClassName($frame['class']),
            new Method($frame['function']),
            ...$frame['args']
        );
    }

    private static function functionCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('file', $frame)) {
            return null;
        }

        return new CallFrame\FunctionCall(
            new FunctionName($frame['function']),
            Url::of('file://'.$frame['file']),
            new Line($frame['line']),
            ...$frame['args']
        );
    }

    private static function internalFunctionCall(array $frame): CallFrame
    {
        return new CallFrame\InternalFunctionCall(
            new FunctionName($frame['function']),
            ...$frame['args']
        );
    }
}
