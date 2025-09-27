<?php

namespace App\Helpers;

class Validator
{
    /**
     * Check if value is not empty.
     */
    public static function required($value): bool
    {
        return !empty(trim((string) $value));
    }

    /**
     * Check if value is a valid email.
     */
    public static function email($value): bool
    {
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Check minimum string length.
     */
    public static function minLength($value, int $min): bool
    {
        return mb_strlen((string) $value) >= $min;
    }

    /**
     * Check maximum string length.
     */
    public static function maxLength($value, int $max): bool
    {
        return mb_strlen((string) $value) <= $max;
    }

    /**
     * Check if two values match.
     */
    public static function match($value, $otherValue): bool
    {
        return $value === $otherValue;
    }

    /**
     * Custom regex validation.
     */
    public static function regex($value, string $pattern): bool
    {
        return (bool) preg_match($pattern, (string) $value);
    }

    /**
     * Check if value is greater than a given value.
     */
    public static function isGreaterThan($value, int $min): bool
    {
        return $value > $min;
    }

    /**
     * Check if value is equal to a given value.
     */
    public static function isEqualTo($value, string $targetValue): bool
    {
        return $value === $targetValue;
    }
}
