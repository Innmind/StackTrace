<?php
declare(strict_types = 1);

namespace Innmind\StackTrace;

use Innmind\Url\Url;
use Innmind\Immutable\Sequence;

final class CallFrames
{
    /**
     * @psalm-pure
     *
     * @return Sequence<CallFrame>
     */
    #[\NoDiscard]
    public static function of(\Throwable $throwable): Sequence
    {
        $frames = [];

        /**
         * @var array{class?: string, type: string, file?: string, function: string, line: int, args?: array} $frame
         */
        foreach ($throwable->getTrace() as $frame) {
            $frames[] = self::methodCall($frame) ??
                self::staticMethodCall($frame) ??
                self::internalMethodCall($frame) ??
                self::internalStaticMethodCall($frame) ??
                self::functionCall($frame) ??
                self::internalFunctionCall($frame);
        }

        /** @var Sequence<CallFrame> */
        return Sequence::of(...$frames);
    }

    /**
     * @psalm-pure
     *
     * @param array{class?: string, type: string, file?: string, function: string, line: int, args?: array} $frame
     */
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

        return CallFrame\MethodCall::of(
            ClassName::of($frame['class']),
            Method::of($frame['function']),
            Url::of('file://'.$frame['file']),
            Line::of($frame['line']),
            ...\array_values($frame['args'] ?? []),
        );
    }

    /**
     * @psalm-pure
     *
     * @param array{class?: string, type: string, file?: string, function: string, line: int, args?: array} $frame
     */
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

        return CallFrame\StaticMethodCall::of(
            ClassName::of($frame['class']),
            Method::of($frame['function']),
            Url::of('file://'.$frame['file']),
            Line::of($frame['line']),
            ...\array_values($frame['args'] ?? []),
        );
    }

    /**
     * @psalm-pure
     *
     * @param array{class?: string, type: string, function: string, args?: array, file?: string, line?: int} $frame
     */
    private static function internalMethodCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('class', $frame)) {
            return null;
        }

        if ($frame['type'] !== '->') {
            return null;
        }

        return CallFrame\InternalMethodCall::of(
            ClassName::of($frame['class']),
            Method::of($frame['function']),
            ...\array_values($frame['args'] ?? []),
        );
    }

    /**
     * @psalm-pure
     *
     * @param array{class?: string, type: string, function: string, args?: array, file?: string, line?: int} $frame
     */
    private static function internalStaticMethodCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('class', $frame)) {
            return null;
        }

        if ($frame['type'] !== '::') {
            return null;
        }

        return CallFrame\InternalStaticMethodCall::of(
            ClassName::of($frame['class']),
            Method::of($frame['function']),
            ...\array_values($frame['args'] ?? []),
        );
    }

    /**
     * @psalm-pure
     *
     * @param array{file?: string, function: string, line: int, args?: array, class?: string, type: string}  $frame
     */
    private static function functionCall(array $frame): ?CallFrame
    {
        if (!\array_key_exists('file', $frame)) {
            return null;
        }

        return CallFrame\FunctionCall::of(
            FunctionName::of($frame['function']),
            Url::of('file://'.$frame['file']),
            Line::of($frame['line']),
            ...\array_values($frame['args'] ?? []),
        );
    }

    /**
     * @psalm-pure
     *
     * @param array{function: string, args?: array, class?: string, type: string, file?: string, line?: int} $frame
     */
    private static function internalFunctionCall(array $frame): CallFrame
    {
        return CallFrame\InternalFunctionCall::of(
            FunctionName::of($frame['function']),
            ...\array_values($frame['args'] ?? []),
        );
    }
}
