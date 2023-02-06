<?php

declare(strict_types=1);

namespace StellarWP\FieldConditions;

use InvalidArgumentException;

/**
 * Sets up the Field Condition library. It currently only provides an optional means of replacing an exception.
 *
 * @unreleased
 */
class Config
{
    /**
     * @var class-string<InvalidArgumentException>
     */
    private static $invalidArgumentExceptionClass = InvalidArgumentException::class;

    /**
     * @unreleased
     *
     * @throws InvalidArgumentException
     */
    public static function throwInvalidArgumentException()
    {
        throw new self::$invalidArgumentExceptionClass(...func_get_args());
    }

    /**
     * @unreleased
     *
     * @return class-string<InvalidArgumentException>
     */
    public static function getInvalidArgumentExceptionClass(): string
    {
        return self::$invalidArgumentExceptionClass;
    }

    /**
     * @unreleased
     *
     * @param class-string<InvalidArgumentException> $invalidArgumentExceptionClass
     */
    public static function setInvalidArgumentExceptionClass(string $invalidArgumentExceptionClass)
    {
        if (!is_a($invalidArgumentExceptionClass, InvalidArgumentException::class, true)) {
            throw new RuntimeException(
                'The invalid argument exception class must extend the InvalidArgumentException'
            );
        }

        self::$invalidArgumentExceptionClass = $invalidArgumentExceptionClass;
    }
}
