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
 * Created 10.09.15 14:38
 */

namespace Iresults\DateTime;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Iresults\DateTime\Exception\InvalidArgumentException;
use Iresults\DateTime\Exception\MissingDateTimeException;

/**
 * Object to specify a date range
 *
 * @package Iresults\DateTime
 */
class AbstractDateRange implements ImmutableInterface, DateRangeInterface
{
    /**
     * @var DateTimeInterface
     */
    protected $start;

    /**
     * @var DateTimeInterface
     */
    protected $end;

    function __construct(DateTimeInterface $start, DateTimeInterface $end)
    {
        if ($start > $end) {
            throw new \UnexpectedValueException('Start date must not be after end date', 1442234594);
        }
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * Returns the range's start
     *
     * @return DateTimeInterface
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * Returns the range's end
     *
     * @return DateTimeInterface
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * Returns if the range is empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->start == $this->end;
    }

    /**
     * Returns the duration of this Time Slot
     *
     * @param bool $inSeconds
     * @return \DateInterval|int
     */
    public function getDuration($inSeconds = false)
    {
        $end = $this->getEnd();
        if (!$end) {
            throw new MissingDateTimeException('Missing end date', 1434462153);
        }
        $start = $this->getStart();
        if (!$start) {
            throw new MissingDateTimeException('Missing start date', 1434462154);
        }

        if ($inSeconds) {
            return $end->getTimestamp() - $start->getTimestamp();
        }

        return $end->diff($start);
    }

    /**
     * Returns a string representation of the Time Slot
     *
     * @return string
     */
    public function toString()
    {
        return $this->getStart()->format('c').'#'.$this->getEnd()->format('c');
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    function __toString()
    {
        return $this->toString();
    }

    /**
     * Create a new Date Range from string in format
     *
     * @param string $dates
     * @return static
     */
    public static function createFromString($dates)
    {
        list($start, $end) = explode('#', $dates);

        return new static(DateTimeFactory::create(trim($start)), DateTimeFactory::create(trim($end)));
    }

    /**
     * Create a new instance from the given Date Range
     *
     * @param DateRangeInterface $dateRange
     * @return static
     */
    public static function createFromDateRange(DateRangeInterface $dateRange)
    {
        return new static($dateRange->getStart(), $dateRange->getEnd());
    }

    /**
     * Create a new instance empty Date Range where the start equals the end
     *
     * @param DateTimeInterface $startAndEndDate
     * @return static
     */
    public static function createEmpty(DateTimeInterface $startAndEndDate = null)
    {
        if ($startAndEndDate === null) {
            $startAndEndDate = DateTimeFactory::current();
        }

        return new static($startAndEndDate, $startAndEndDate);
    }

    /**
     * Creates a new instance with the given start and span
     *
     * @param DateTimeInterface $start
     * @param string            $span
     * @return static
     */
    public static function createWithSpan(DateTimeInterface $start, $span)
    {
        switch ($span) {
            case self::UNIT_HALF_HOUR:
                $span = '+ 30 minutes';
                break;
            case self::UNIT_HOUR:
                $span = '+ 1 hour';
                break;
            case self::UNIT_DAY:
                $span = '+ 1 day';
                break;

        }
        if (!$start instanceof DateTime && !$start instanceof DateTimeImmutable) {
            throw InvalidArgumentException::exceptionWithArgumentNameTypeAndValue(
                'start',
                [DateTime::class, DateTimeImmutable::class],
                $start
            );
        }
        $end = clone $start;
        $end->modify($span);

        return new static($start, $end);
    }

    /**
     * Returns an iterator for the given unit
     *
     * @param string $unit
     * @return DateRangeIterator
     */
    public function getIteratorForUnit($unit)
    {
        return new DateRangeIterator($this, $unit);
    }

    /**
     * Debugging
     *
     * @return array
     */
    public function _ir_debug()
    {
        return array(
            'start' => $this->getStart()->format('c'),
            'end'   => $this->getEnd()->format('c'),
        );
    }
}
