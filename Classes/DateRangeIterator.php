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
 * Created 14.09.15 12:46
 */


namespace Iresults\DateTime;


use Countable;
use DateInterval;
use DatePeriod;
use DateTimeInterface;
use Iresults\DateTime\TimeSlot\TimeSlot;
use Iresults\DateTime\Exception\InvalidArgumentException;

use Iterator;

/**
 * Iterator over a date range
 *
 * @package Iresults\DateTime
 */
class DateRangeIterator implements Iterator, Countable, ImmutableInterface
{
    /**
     * @var DatePeriod
     */
    protected $datePeriod;

    /**
     * @var DateTimeInterface[]
     */
    protected $slots;

    /**
     * @var int
     */
    protected $index = 0;

    /**
     * DateRangeIterator constructor.
     *
     * @param DateRangeInterface $dateRange
     * @param string             $unit
     */
    public function __construct(DateRangeInterface $dateRange, $unit)
    {
        $this->createDatePeriod($dateRange, $unit);
    }

    public function current()
    {
        $slots = $this->getSlots();
        if (isset($slots[$this->index])) {
            return $slots[$this->index];
        }

        return null;
    }

    public function next()
    {
        $this->index++;
    }


    public function key()
    {
        return $this->index;
    }

    public function valid()
    {
        return $this->current() !== null;
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function count()
    {
        return count($this->getSlots());
    }


    /**
     * @param DateRangeInterface $dateRange
     * @param string             $unit
     * @return array
     */
    protected function createDatePeriod(DateRangeInterface $dateRange, $unit)
    {
        if (is_string($unit)) {
            switch ($unit) {
                case DateRange::UNIT_DAY:
                    $interval = new DateInterval('P1D');
                    break;

                case DateRange::UNIT_HOUR:
                    $interval = new DateInterval('PT1H');
                    break;

                case DateRange::UNIT_HALF_HOUR:
                    $interval = new DateInterval('PT30M');
                    break;

                default:
                    if ($unit[0] !== 'P') {
                        throw new InvalidArgumentException(sprintf('Invalid unit "%s"', $unit));
                    }
                    $interval = new DateInterval($unit);
            }
        } elseif ($unit instanceof DateInterval) {
            $interval = $unit;
        } else {
            throw InvalidArgumentException::exceptionWithArgumentNameTypeAndValue(
                'unit',
                ['string', 'DateInterval'],
                $unit,
                1443194596
            );
        }

        $start = $dateRange->getStart();
        $end = $dateRange->getEnd();

        // Add an extra second to get an extra element to specify the end of the last TimeSlot
        if (method_exists($end, 'modify')) {
            $end = clone $end;
            $end = $end->modify('+2 sec');
        }

        $this->datePeriod = new DatePeriod($start, $interval, $end);
    }

    /**
     * @return TimeSlot[]
     */
    protected function getSlots()
    {
        if (!$this->slots) {
            $rawSlots = iterator_to_array($this->datePeriod);
            if (count($rawSlots) < 1) {
                return $this->slots = array();
            }

            /** @var DateTimeInterface $currentSlot */
            /** @var DateTimeInterface $previousSlot */
            $previousSlot = null;
            $timeSlots = array();

            foreach ($rawSlots as $currentSlot) {
                if ($previousSlot) {
                    $timeSlots[] = new TimeSlot($previousSlot, $currentSlot);
                }
                $previousSlot = $currentSlot;
            }

            $this->slots = $timeSlots;
        }

        return $this->slots;
    }
}
