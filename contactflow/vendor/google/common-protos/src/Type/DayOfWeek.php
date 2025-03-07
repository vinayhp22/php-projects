<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: google/type/dayofweek.proto

namespace Google\Type;

use UnexpectedValueException;

/**
 * Represents a day of the week.
 *
 * Protobuf type <code>google.type.DayOfWeek</code>
 */
class DayOfWeek
{
    /**
     * The day of the week is unspecified.
     *
     * Generated from protobuf enum <code>DAY_OF_WEEK_UNSPECIFIED = 0;</code>
     */
    const DAY_OF_WEEK_UNSPECIFIED = 0;
    /**
     * Monday
     *
     * Generated from protobuf enum <code>MONDAY = 1;</code>
     */
    const MONDAY = 1;
    /**
     * Tuesday
     *
     * Generated from protobuf enum <code>TUESDAY = 2;</code>
     */
    const TUESDAY = 2;
    /**
     * Wednesday
     *
     * Generated from protobuf enum <code>WEDNESDAY = 3;</code>
     */
    const WEDNESDAY = 3;
    /**
     * Thursday
     *
     * Generated from protobuf enum <code>THURSDAY = 4;</code>
     */
    const THURSDAY = 4;
    /**
     * Friday
     *
     * Generated from protobuf enum <code>FRIDAY = 5;</code>
     */
    const FRIDAY = 5;
    /**
     * Saturday
     *
     * Generated from protobuf enum <code>SATURDAY = 6;</code>
     */
    const SATURDAY = 6;
    /**
     * Sunday
     *
     * Generated from protobuf enum <code>SUNDAY = 7;</code>
     */
    const SUNDAY = 7;

    private static $valueToName = [
        self::DAY_OF_WEEK_UNSPECIFIED => 'DAY_OF_WEEK_UNSPECIFIED',
        self::MONDAY => 'MONDAY',
        self::TUESDAY => 'TUESDAY',
        self::WEDNESDAY => 'WEDNESDAY',
        self::THURSDAY => 'THURSDAY',
        self::FRIDAY => 'FRIDAY',
        self::SATURDAY => 'SATURDAY',
        self::SUNDAY => 'SUNDAY',
    ];

    public static function name($value)
    {
        if (!isset(self::$valueToName[$value])) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no name defined for value %s', __CLASS__, $value));
        }
        return self::$valueToName[$value];
    }


    public static function value($name)
    {
        $const = __CLASS__ . '::' . strtoupper($name);
        if (!defined($const)) {
            throw new UnexpectedValueException(sprintf(
                    'Enum %s has no value defined for name %s', __CLASS__, $name));
        }
        return constant($const);
    }
}

