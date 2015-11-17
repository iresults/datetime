<?php
/*
 *  Copyright notice
 *
 *  (c) 2015 Andreas Thurnheer-Meier <tma@iresults.li>, iresults
 *  Daniel Corn <cod@iresults.li>, iresults
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 */

/**
 * @author COD
 * Created 25.09.15 17:43
 */


namespace Iresults\DateTime;


use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use Iresults\DateTime\Exception\InvalidArgumentException;

/**
 * Factory for DateTime
 *
 * @package Iresults\DateTime
 */
abstract class DateTimeFactory
{
    /**
     * @var DateTimeInterface
     */
    private static $simulatedCurrentTime;

    /**
     * Create a new Date Time
     *
     * @return DateTime
     */
    public static function current()
    {
        if (static::$simulatedCurrentTime) {
            return static::$simulatedCurrentTime;
        }

        return new DateTime();
    }

    /**
     * Create a new Date Time
     *
     * @param int|string|DateTime|DateTimeImmutable $input
     * @return DateTime
     */
    public static function create($input)
    {
        if ($input === 'current') {
            return static::current();
        }
        if ($input instanceof DateTimeImmutable) {
            $input = $input->getTimestamp();
        }

        if ($input === null) {
            $date = new DateTime();
        } elseif ($input instanceof DateTime) {
            $date = clone $input;
        } elseif (is_int($input)) {
            $date = new DateTime('@'.$input);
        } elseif (is_string($input)) {
            if (static::isIntegerString($input)) {
                $date = new DateTime('@'.$input);
            } else {
                $date = new DateTime($input);
            }
        } else {
            throw InvalidArgumentException::exceptionWithArgumentNameTypeAndValue(
                'input',
                ['int', 'string', 'DateTime', 'DateTimeImmutable'],
                $input
            );
        }
        $date->setTimezone(new DateTimeZone(date_default_timezone_get()));

        return $date;
    }

    /**
     * Create a new Date Time but with the given time
     *
     * @param int|string|DateTime|DateTimeImmutable $input
     * @param Time                                  $time
     * @return DateTime
     */
    public static function createWithTime($input, Time $time)
    {
        $date = static::create($input);
        $date->setTime($time->getHour(), $time->getMinute(), $time->getSecond());

        return $date;
    }

    /**
     * @param DateTimeInterface $simulatedCurrentTime
     * @return DateTimeInterface
     * @internal
     */
    public static function simulateCurrentTime(DateTimeInterface $simulatedCurrentTime)
    {
        $lastCurrent = static::$simulatedCurrentTime;
        static::$simulatedCurrentTime = $simulatedCurrentTime;

        return $lastCurrent;
    }

    /**
     * @param string $input
     * @return bool
     */
    protected static function isIntegerString($input)
    {
        if (!is_numeric($input)) {
            return false;
        }

        return (string)intval($input) === $input;
    }
}