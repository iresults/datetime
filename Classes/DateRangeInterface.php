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
 * Created 18.09.15 14:37
 */
namespace Iresults\DateTime;

use DateTimeInterface;

/**
 * Object to specify a date range
 *
 * @package Iresults\DateTime
 */
interface DateRangeInterface
{
    const UNIT_DAY = 'd';
    const UNIT_HOUR = 'h';
    const UNIT_HALF_HOUR = '30m';

    /**
     * Returns the range's start
     *
     * @return DateTimeInterface
     */
    public function getStart();

    /**
     * Returns the range's end
     *
     * @return DateTimeInterface
     */
    public function getEnd();

    /**
     * Returns if the range is empty
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Returns the duration
     *
     * @param bool $inSeconds
     * @return \DateInterval|int
     */
    public function getDuration($inSeconds = false);

    /**
     * Returns a string representation of the range
     *
     * @return string
     */
    public function toString();

    /**
     * Create a new Range from string in format
     *
     * @param string $dates
     * @return static
     */
    public static function createFromString($dates);
}